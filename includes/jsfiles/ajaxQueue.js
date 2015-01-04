/*** copyright 2013 White Pond Design LLC ***/
/**
* Ajax Queue Plugin
*
* By john nitzsche
* Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
*
* Code inspired by the work of Robert Lynch.
*/

(function($) {
  $.ajaxQueue = function(ajaxOptions, queueName) {
    queueName = queueName || "ajax";

    //xxx if first call since load xxx//
    if(typeof $document == 'undefined') {
      var $document = $(document);
      window.ajaxQueueCount = {};
    }

    //xxx some initial setups xxx//
    if(typeof(ajaxQueueCount[queueName]) == 'undefined') {
      ajaxQueueCount[queueName] = 0;
    }
    
    //xxx add complete callback to incoming ajax function xxx//
    ajaxOptions.complete = function() {
      $document.dequeue( queueName );
      window.ajaxQueueCount[queueName] -=1;
    };
    
    //xxx add the new item to the queue xxx//
    window.ajaxQueueCount[queueName] +=1;
    $document.queue(queueName, function(){
      $.ajax( ajaxOptions ).done(ajaxOptions.done).fail(ajaxOptions.fail).always(ajaxOptions.always);
    });
    
    //xxx execute if only one xxx//
    if(ajaxQueueCount[queueName] ==1) {
      $document.dequeue( queueName );
    }

  } // close $.ajaxQueue = function
 
})(jQuery);