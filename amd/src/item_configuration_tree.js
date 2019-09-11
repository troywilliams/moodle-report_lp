// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

define(
    [
        'jquery',
        'core/sortable_list',
        'core/ajax',
        'core/templates',
        'core/notification',
        'core/log'
    ],
    function(
        $,
        SortableList,
        Ajax,
        Templates,
        Notification,
        Log
    )
    {
        var init = function(id) {
            // Initialise sortable for the given list.
            Log.debug('Loaded ' + id);
            //var sortableChildrenListElement = $(id + ' [data-child-id]');
            //var childrenItemListElement = $(id);
            var childrenItemList = new SortableList(
                $('[data-list="children"]'),
                {moveHandlerSelector: '.move-child [data-drag-type=move]'}
            );
            //Log.debug(sortableChildrenList);
            $('[data-child-id]').on('sortablelist-drop', function(evt, info) {
                evt.stopPropagation(); // Important for nested lists to prevent multiple targets.
                Log.debug(info);
            });
            var grandChildrenItemList = new SortableList(
                $('[data-list="grand-children"]'),
                {moveHandlerSelector: '.move-grand-child [data-drag-type=move]'}
            );
            $('[data-child-id], [data-grand-child-id]').on('sortablelist-dragstart',
                function(evt, info) {
                    setTimeout(function() {
                        $('.sortable-list-is-dragged').width(info.element.width());
                    }, 501);
                }
            );

        };

        return {
            init: init
    };
});
