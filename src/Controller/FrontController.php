<?php

namespace Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class FrontController{

  public function indexAction(Application $app, Request $request) {
    $tagManager = $app['models']('Tag');
    $q = $tagManager->query()->where('visible = 1')->orderBy('prio', 'DESC');
    $tags = $app['models']('Tag')->fetchAll($q);

    $response = new Response(
      $app['twig']->render('index.html.twig', compact('tags'))
    );
    return $response;

    
  }

  public function creditsAction(Application $app, Request $request) {

    $response = new Response(
      $app['twig']->render('credits.html.twig')
    );
    return $response;

    
  }

  public function searchAction(Application $app, Request $request){

    $tags = $request->get('tags', []);
    if(!empty($tags)){
      $videos = $app['hubtraffic']->searchVideos(["tags"=>$tags]);  
    }else{
      $videos = [];
    }
    
    $videos = array_map(function($i){
      return [
                "url" => $i['url'],
                "thumb" => $i['thumb'],
                "title" => $i['title']
              ];
    },
    $videos);
    shuffle($videos);
    $videos = array_slice($videos, 0, 24); 
    return $app->json(["success" => $videos], 200);
  }
  public function proxyimageAction(Application $app, Request $request){
    $file = $request->get('url');
    $stream = function () use ($file) {
       readfile($file);
   };
   return $app->stream($stream, 200, array('Content-Type' => 'image/jpeg'));
  }
}