<?php

namespace Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class FrontController{

  public function indexAction(Application $app, Request $request) {
    $tags = $app['models']('Tag')->fetchAll();

    $response = new Response(
      $app['twig']->render('index.html.twig', compact('tags'))
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


    return $app->json(["success" => $videos], 404);
  }
  
}