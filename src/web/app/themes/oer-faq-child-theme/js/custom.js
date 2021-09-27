jQuery(document).ready(function ($) {

  var $input = $('.searchform input[name="s"]');
  var $searchresults = $('.searchform .ajax-searchresults');
  var selectedResult = -1;
  var posts = [];
  $searchresults.css({
    zIndex: 1,
  });
  $input.on('input', throttle(update, 1000));

  function update() {
    var val = $input.val();
    $.ajax({
      url: $input.attr('data-ajax'),
      data: { action: 'ajax_search_results', s: val },
      success: function(result) {
        posts = result.data.posts;
        $searchresults.html('')
        if (val.trim().length > 0) {
          var list = $('<div class="list-group shadow"></div>');
          var item = $('<a class="list-group-item list-group-item-action list-group-item-light"></a>');
          item.append('Nach ')
          item.append($('<em></em>').text(val));
          item.append(' suchen');
          item.attr('href', '/?s=' + encodeURIComponent(val));
          list.append(item);

          posts.forEach( function(entry) {
              var item = $('<a class="list-group-item list-group-item-action"></a>');
              item.text(entry.title);
              item.append($('<span style="font-size: 0.8em"></span>').text(' (score: ' + new Number(entry.score).toFixed(2) + ')'));
              item.attr('href', entry.url);
              list.append(item);
          });

          $searchresults.append(list);
        }

        // if (result.data.count > result.data.posts.length) {
        //   $searchresults.append($('<div class="text-mutesd">Drücken Sie Enter um weitere Ergebnisse zu sehen.</div>'));
        // }
        selectedResult = -1;
        updateSelected();
      }
    });
  }

  function  updateSelected() {
    $searchresults.find('a.active').removeClass('active');
    $($searchresults.find('a')[selectedResult + 1]).addClass('active');
  }

  $('.searchform').on('keyup', function(e) {
    if (e.key === 'ArrowDown' && posts.length)  {
      if (selectedResult === posts.length - 1) {
        selectedResult = -1;
      } else {
        selectedResult++;
      }
      updateSelected();
    } else if (e.key === 'ArrowUp' && posts.length)  {
      if ( selectedResult === -1) {
        selectedResult = posts.length - 1;
      } else {
        selectedResult--;
      }
      updateSelected();
    }
  });


  $('.searchform').on('keypress', function(e) {
    if (e.key == "Enter") {
      if (selectedResult >= 0 && selectedResult <= posts.length - 1) {
        e.preventDefault();
        const href = $($searchresults.find('a')[selectedResult + 1]).attr('href');
        window.location = href;
      }
    } else if (e.key == "Escape") {
      $searchresults.html('');
    }

  });

}); // jQuery End


function throttle(func, wait) {
  var timeout;
  return function() {
      var context = this, args = arguments;
      if (!timeout) {
          // the first time the event fires, we setup a timer, which
          // is used as a guard to block subsequent calls; once the
          // timer's handler fires, we reset it and create a new one
          timeout = setTimeout(function() {
              timeout = null;
              func.apply(context, args);
          }, wait);
      }
  }
}


// vanilla JavaScript
var links = document.links;

for (var i = 0, linksLength = links.length; i < linksLength; i++) {
  if (links[i].hostname != window.location.hostname) {
    links[i].target = '_blank';
  }
}
