angular
  .module('pictopornApp', [])
  .config(function($interpolateProvider){
          $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
        })
  .controller('SearchController', function($scope, $http) {
    var search = this;
    search.tags = [];
    search.videos = [];
    search.loading = false;
 
    search.toggleTag = function($event, text) {
      console.log(text);
      var i = search.tags.indexOf(text)
      if( i > -1){
        search.tags.splice(i,1);
      }else{
        search.tags.push(text);  
      }
      $($event.currentTarget).toggleClass('selected');
      search.updateVideos();
    };

    search.updateVideos = function(){
      search.videos = [];
      search.loading = true;
      $http.get('/search',
                {
                  params: { "tags[]": search.tags }
                }
            )
           .success(function(data, status, headers, config) {
                search.videos = data.success;
                search.loading = false;
           })
           .error(function(data, status, headers, config) {
            console.log(data);
           });

    }
  })
  .directive("drawing", function(){
  return {
    restrict: "A",
    link: function(scope, element, attrs){
      var thumb_url = '/image?url='+attrs.thumb;
      var ctx = element[0].getContext('2d');

      // variable that decides if something should be drawn on mousemove
      var drawing = false;
      var thumbImage = new Image();
      thumbImage.onload = function(){
        console.log(thumb_url)
        ctx.drawImage(thumbImage, 0, 0, 160, 120, 0, 0, 140, 105);
        AtkinsonDithering.run(ctx, 140, 105);
      }
      thumbImage.src = thumb_url;


    }
  };
});


var AtkinsonDithering = {
  grayscale : function(image) {
    var RADIX = 15;
    var i;
    image.gray = new Array(image.width * image.height);
    for (var i = 0; i < image.gray.length; i++) {
      // Luminosity:
      image.gray[i] = parseInt(
        (0.2126 * image.data[i << 2]) +
        (0.7152 * image.data[(i << 2) + 1]) +
        (0.0722 * image.data[(i << 2) + 2]), RADIX
      );
    }
  },

  spread : function(image) {
    var p;
    for (var i = 0; i < image.data.length; i += 4) { 
      p = image.gray[i >> 2];
      image.data[i] = p; 
      image.data[i + 1] = p; 
      image.data[i + 2] = p; 
      // Skipping alpha channel.
    }
  },

  turn_atkinson: function(ctx, width, height){
    var GRAYS = 256;
    var THRESHOLD = new Array();


    for (var i = 0; i < GRAYS; i++) {
      THRESHOLD.push(i < (GRAYS >> 1) ? [0] : [GRAYS - 1]);
    }
    var data = ctx.getImageData(0, 0, width, height);
    console.log(data);
      AtkinsonDithering.grayscale(data);
      for (var y = 0; y < data.height; y++) {
        for (var x = 0; x < data.width; x++) {
          var i = (y * data.width) + x;
          gray_old = data.gray[i];
          gray_new = THRESHOLD[gray_old];
          gray_err = (gray_old - gray_new) >> 3;
          data.gray[i] = gray_new;
          var NEAR = [
            [x+1, y], [x+2, y], [x-1, y+1], [x, y+1], [x+1, y+1], [x, y+2]
          ];
          var near_x = 0;
          var near_y = 0;
          for (var n = 0; n < NEAR.length; n++) {
            near_x = NEAR[n][0];
            near_y = NEAR[n][1];
            if (near_x >= 0) {
              if (near_x <= width) {
                if (near_y >= 0) {
                  if (near_y <= height) {
                    data.gray[
                      ((near_y * data.width) + near_x)
                    ] += gray_err;
                  }
                }
              }
            }
          }
        }
      }
      AtkinsonDithering.spread(data);
      ctx.putImageData(data, 0, 0);
  },

  run : function(ctx, w, h){
    AtkinsonDithering.turn_atkinson(ctx, w, h);
  },


};
