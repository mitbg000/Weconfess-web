// on install - the application shell cached
self.addEventListener ('install', function(event) {
    event.waitUntill(
        caches.open('service-worker-cache').then(function(cache) {
         
          // Static files that make up the application shell are cached
             return cache.add('app.blade.php'); // if you have css files and app.js files 
             
            // please add here as well as to be cached!we havent added as we have a simple app
             // but your website uses them
             return cache.match(evt.request).then(cacheResponse => cacheResponse || fetch(evt.request).then(networkResponse => {
                cache.put(evt.request, networkResponse.clone());
                return networkResponse;
             
        }) 
    );
}));
});

  //with request network
  self.addEventListener('fetch', function(event) {
    event.respondWith(
        //Try the cache
        caches.match(event.request).then(function(response) {
            //return it if there is a response, or else fetch again
            return response || fetch(event.request);
        })
    );
});
