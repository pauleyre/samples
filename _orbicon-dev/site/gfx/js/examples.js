//live comment preview
$(document).ready(function() {
  var $comment = $('#comment'), commentVal = '';
  if ( $('ol.commentlist li').length > 120 ) return;
  $comment
    .one('focus',function() {
      $comment.parent().after(['<div id="preview-box">',
        '<div class="comment-by">',
        'Live Comment Preview',
        '</div>',
        '<div id="live-preview"></div>',
        '</div>'].join('')
      );
    })
    .keyup(function() {
      commentVal = $(this).val();
      commentVal = commentVal.replace(/\n\n+/g, '<br /><br />').replace(/\n/g, "<br />");
      $('#live-preview').html( commentVal );
    });
});



$(document).ready(function() {
// search form label thing 
  var $s = $('#s');
  var searchLabel = $('#searchform label').css({visibility: 'hidden'}).text();

  function restoreSearch() {   
    if ($s.val() == '') {
      $s.val(searchLabel);
    } 
    if ($s.val() == searchLabel) {
      $s.addClass('faded');
    }
  }
  
  restoreSearch();
  
  $s.focus(function() {
    $s.removeClass('faded');
    if ($s.val() == searchLabel) {
      $s.val('');
    }
  })
  .blur(restoreSearch);

//  avoid widows
  var h2Text = '';
  $('h2 a').each(function(index) {
    h2Text  = $(this).text().replace(/ (\w+)$/,'&nbsp;$1');
    $(this).html(h2Text);
  });
  
});

// basic show and hide
 $(document).ready(function() {
   $('#hideh1').click( function() {
    $('div.showhide,h1').hide();
   });
   $('#showh1').click( function() {
    $('div.showhide,h1').show();
   });
   $('#toggleh1').click( function() {
    $('div.showhide,h1').toggle();
   });

//blur link after click   
   $('a.fun').click(function() {
     this.blur();
     return false;
   });   
 });

// auto page contents
$(document).ready(function() {
  if ( $('#content h2').length > 1) {
    $('<div id="page-contents"></div>')
      .prepend('<h3>Page Contents</h3>')
      .append('<div></div>')
      .prependTo('body');
      
    var thisId = '';
    $('#content h2').each(function(index) {
      $this = $(this);
      thisId = this.id;
      $this
        .clone()
        .find('a')
          .attr({
            'title': 'jump to ' + $this.text(), 
            'href': '#' + thisId
          })
        .end()
        .attr('id', 'pc-' + index)
        .appendTo('#page-contents div');
    });
      
    $('#page-contents h3').click(function() {
      $(this).toggleClass('arrow-down')
        .next().slideToggle('fast');
    });
  }
});

//set hover class for anything
$(document).ready(function() {
  $('#hover-demo2 p').hover(function() {
    $(this).addClass('pretty-hover');
  }, function() {
    $(this).removeClass('pretty-hover');
  });
});

//smooth scrolling

$(document).ready(function() {
  function filterPath(string) {
	return string
	  .replace(/^\//,'')
	  .replace(/(index|default).[a-zA-Z]{3,4}$/,'')
	  .replace(/\/$/,'');
  }
  $('a[href*=#]').each(function() {
	if ( filterPath(location.pathname) == filterPath(this.pathname)
	&& location.hostname == this.hostname
	&& this.hash.replace(/#/,'') ) {
	  var $target = $(this.hash), target = this.hash;
	  if ($target) {
  		var targetOffset = $target.offset().top;
  		$(this).click(function() {
  		  $('html, body').animate({scrollTop: targetOffset}, 400, function() {
  			  if (!location.hash) window.location += target;
  		  });
  		});
	  }
	}
  });
});

// get IE to recognize <abbr> tag 
$(document).ready(function() {
  document.createElement('abbr');
});

