// Browser Window Size and Position
// copyright Stephen Chapman, 3rd Jan 2005, 8th Dec 2005
// you may copy these functions but please keep the copyright notice as well
screenutils = {
  pageWidth : function () { return window.innerWidth != null ? 
  window.innerWidth : document.documentElement && document.documentElement.clientWidth ?
  document.documentElement.clientWidth : document.body != null ?
  document.body.clientWidth : null; }
, pageHeight : function () { return  window.innerHeight != null ?
  window.innerHeight : document.documentElement && document.documentElement.clientHeight ?
  document.documentElement.clientHeight : document.body != null ?
  document.body.clientHeight : null; } 
, posLeft : function () { return typeof window.pageXOffset != 'undefined' ?
  window.pageXOffset :document.documentElement && document.documentElement.scrollLeft ?
  document.documentElement.scrollLeft : document.body.scrollLeft ?
  document.body.scrollLeft : 0}
, posTop : function () { return typeof window.pageYOffset != 'undefined' ?
  window.pageYOffset : document.documentElement && document.documentElement.scrollTop ?
  document.documentElement.scrollTop : document.body.scrollTop ?
  document.body.scrollTop : 0; }
, posRight : function ()  { return utils.posLeft() + utils.pageWidth();  }
, posBottom : function () { return utils.posTop()  + utils.pageHeight(); }
, returndimstring : function () { return "right: " + utils.posRight() ; }
} // close utils

