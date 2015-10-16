/**
 * window    for interval
 * document  for on-ready
 * jQuery    for jQuery...
 */
var FdaBigScreen = (function(window, document, jQuery) {
    var self = jQuery({}),
        panelClass = 'board-info',
        autoUpdateTogglerId = 'fda-toggle-auto-update',
        autoUpdateEnabled = false,
        boardPanels = [],
        lastUpdateIndex = 0,

        updateBoardPanel = function (boardPanel) {
            var boardId = boardPanel.data('board-id'),
                url = self.urls.boardInfo.replace('boardId', boardId);
            boardPanel.load(url);
        },

        updateAllBoardPanels = function () {
            boardPanels.forEach(function (boardPanel) {
                updateBoardPanel(boardPanel);
            });
        },

        alternatingUpdate = function() {
            ++lastUpdateIndex;
            if (lastUpdateIndex == boardPanels.length) {
                lastUpdateIndex = 0;
            }
            updateBoardPanel(boardPanels[lastUpdateIndex]);
        },

        collectBoardPanels = function () {
            jQuery('.'+panelClass).each(function () {
                var elem = jQuery(this);
                boardPanels.push(elem);
            });
        },

        initAutoUpdateToggler = function () {
            jQuery('#'+autoUpdateTogglerId).click(function (event) {
                var btn = jQuery(this);
                event.preventDefault();

                autoUpdateEnabled = !autoUpdateEnabled;

                if (autoUpdateEnabled) {
                    btn.removeClass('btn-danger');
                    btn.addClass('btn-success');
                } else {
                    btn.removeClass('btn-success');
                    btn.addClass('btn-danger');
                }
            });
        },

        init = function () {
            initAutoUpdateToggler();
            collectBoardPanels();
            updateAllBoardPanels();

            window.setInterval(function () {
                if (autoUpdateEnabled) {
                    alternatingUpdate();
                }
            }, 1000);
        };

    jQuery(document).ready(function() {
        init();
    });

    self.urls = {
        boardInfo: ''
    };

    return self;
}(window, document, jQuery));
