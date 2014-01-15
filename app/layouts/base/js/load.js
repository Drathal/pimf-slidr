Modernizr.addTest('webkit', function() {
  return new RegExp(" AppleWebKit/").test(navigator.userAgent);
});

Modernizr.addTest('mobile', function() {
  return new RegExp(" Mobile/").test(navigator.userAgent);
});

Modernizr.addTest("mobileDim", function() {
  if (Modernizr.mq('only screen and (max-width: 320px) and (orientation: portrait)')) {
    return true;
  } else  {
    return Modernizr.mq('only screen and (max-width: 480px) and (orientation: landscape)');
  }
});

Modernizr.addTest("tabletDim", function() {
  if (Modernizr.mq('only screen and (min-width: 600px) and (orientation:portrait)')) {
    return true;
  } else {
    return Modernizr.mq('only screen and (max-width: 1024px) and (orientation:landscape)');
  }
});

Modernizr.addTest("desktopDim", Modernizr.mq('only screen and (min-width: 802px)'));

Modernizr.load([ 
  {
    load : ['js/jquery.min.js','js/presenter.js','js/syntax/scripts/shCore.js']
  },
  {
    load : ['js/syntax/scripts/shBrushJScript.js','js/syntax/scripts/shBrushPlain.js','js/syntax/scripts/shBrushCss.js'],
    complete : function() {
      SyntaxHighlighter.highlight();
      $('.presentation').presenter();
      
    }
  }
]);
