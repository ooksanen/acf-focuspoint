(function($) {
    acf.fields.focuspoint = acf.field.extend({

        type: 'focuspoint',
        $el: null,

        actions: {
            'ready': 'initialize',
            'append': 'initialize'
        },

        events: {
            'click a[data-name="add"]': 'add',
            'click a[data-name="edit"]': 'edit',
            'click a[data-name="remove"]': 'remove',
            'change input[type="file"]': 'change'
        },

        focus: function() {
            // get elements
            this.$el = this.$field.find('.acf-image-uploader');
            // get options
            this.o = acf.get_data(this.$el);
        },

        initialize: function($el) {

            // add attribute to form
            if (this.o.uploader == 'basic') {
                this.$el.closest('form').attr('enctype', 'multipart/form-data');
            }

            // Cache jquery selectors
            // Values to get/set
            var $id = $el.find('[data-name="acf-focuspoint-img-id"]'),

                // Elements to get/set 
                $fp = $el.find('.acf-focuspoint'),
                $img = $el.find('.acf-focuspoint-img'),
                $selection = $el.find('.focuspoint-selection-layer');

            // Hold/get our values
            var values = {
                id: $id.val(),
                size: $fp.data('preview_size')
            };

            // DOM elements
            var img = $img.get(0);

            $selection.on('click', function(event) {

                var iw = $(this).outerWidth();
                var ih = $(this).outerHeight();
                var px = event.offsetX;
                var py = event.offsetY;
                var y_percentage = Math.round(((py / ih * 100) + Number.EPSILON) * 100) / 100;
                var x_percentage = Math.round(((px / iw * 100) + Number.EPSILON) * 100) / 100;
                $(this).siblings('.focal-point-picker').css({
                    top: y_percentage + '%',
                    left: x_percentage + '%'
                });
                $top = $(this).parents('.acf-focuspoint').children('[data-name="acf-focuspoint-top"]');
                $left = $(this).parents('.acf-focuspoint').children('[data-name="acf-focuspoint-left"]');
                $top.val(y_percentage).trigger('change');
                $left.val(x_percentage).trigger('change');

            });
        },

        add: function() {
            // reference
            var self = this,
                $field = this.$field;

            // get repeater
            var $repeater = acf.get_closest_field(this.$field, 'repeater');

            // popup
            var frame = acf.media.popup({

                title: acf._e('image', 'select'),
                mode: 'select',
                type: 'image',
                field: acf.get_field_key($field),
                multiple: $repeater.exists(),
                library: this.o.library,
                mime_types: this.o.mime_types,

                select: function(attachment, i) {

                    // select / add another image field?
                    if (i > 0) {
                        // vars
                        var key = acf.get_field_key($field),
                            $tr = $field.closest('.acf-row');

                        // reset field
                        $field = false;

                        // find next image field
                        $tr.nextAll('.acf-row:visible').each(function() {

                            // get next $field
                            $field = acf.get_field(key, $(this));

                            // bail early if $next was not found
                            if (!$field) {
                                return;
                            }

                            // bail early if next file uploader has value
                            if ($field.find('.acf-image-uploader.has-value').exists()) {
                                $field = false;
                                return;
                            }

                            // end loop if $next is found
                            return false;

                        });

                        // add extra row if next is not found
                        if (!$field) {

                            $tr = acf.fields.repeater.doFocus($repeater).add();

                            // bail early if no $tr (maximum rows hit)
                            if (!$tr) {
                                return false;
                            }

                            // get next $field
                            $field = acf.get_field(key, $tr);

                        }

                    }

                    // focus
                    self.doFocus($field);

                    // render
                    self.render(self.prepare(attachment));

                }

            });

        },

        prepare: function(attachment) {
            // vars
            var image = {
                id: attachment.id,
                url: attachment.attributes.url
            };

            //console.log( 'Hep!' );

            leftVal = this.$el.find('[data-name="acf-focuspoint-left"]').val();
            topVal = this.$el.find('[data-name="acf-focuspoint-top"]').val();

            if( leftVal && topVal ){
                this.$el.find('.focal-point-picker').css({
                    top: topVal + '%',
                    left: leftVal + '%'
                });
            }
            else {
                this.$el.find('.focal-point-picker').css({
                    top: '50%',
                    left: '50%'
                });
            }
            this.$el.find('.focuspoint-image.no-image').removeClass('no-image').addClass('has-image');

            // check for preview size
            if (acf.isset(attachment.attributes, 'sizes', this.o.preview_size, 'url')) {
                image.url = attachment.attributes.sizes[this.o.preview_size].url;
            }

            // return
            return image;

        },

        render: function(image) {

            // set atts
            this.$el.find('[data-name="acf-focuspoint-img"]').attr('src', image.url);
            this.$el.find('[data-name="acf-focuspoint-img-id"]').val(image.id).trigger('change');

            // set div class
            this.$el.addClass('has-value');

        },

        edit: function() {
            // reference
            var self = this;

            // popup
            var frame = acf.media.popup({

                title: acf._e('image', 'edit'),
                type: 'image',
                button: acf._e('image', 'update'),
                mode: 'edit',
                id: id,

                select: function(attachment, i) {
                    self.render(self.prepare(attachment)).trigger('change');
                }

            });

        },
        
        removeAttachment: function(){
            this.render( false );
        },

        remove: function() {
            // vars
            var attachment = {
                id: '',
                url: ''
            };

            this.removeAttachment();

            // remove position
            this.$el.find('[data-name="acf-focuspoint-top"]').val('');
            this.$el.find('[data-name="acf-focuspoint-left"]').val('');

            // remove class
            this.$el.find('.focuspoint-image.has-image').removeClass('has-image').addClass('no-image');
            this.$el.removeClass('has-value').trigger('change');

        },

        change: function(e) {
            this.$el.find('[data-name="acf-focuspoint-img-id"]').val(e.$el.val()).trigger('change');
        }

    });


    if (typeof acf.add_action !== 'undefined') {

        /*
         *  ready append (ACF5)
         *
         *  These are 2 events which are fired during the page load
         *  ready = on page load similar to $(document).ready()
         *  append = on new DOM elements appended via repeater field
         *
         *  @type    event
         *  @date    20/07/13
         *
         *  @param   $el (jQuery selection) the jQuery element which contains the ACF fields
         *  @return  n/a
         */

        acf.add_action('ready append', function($el) {

            // search $el for fields of type 'focuspoint'
            acf.get_fields({ type: 'focuspoint' }, $el).each(function() {

                //initialize_field($(this));
                acf.fields.focuspoint.initialize($el)

            });

        });


    } else {


        /*
         *  acf/setup_fields (ACF4)
         *
         *  This event is triggered when ACF adds any new elements to the DOM.
         *
         *  @type    function
         *  @since   1.0.0
         *  @date    01/01/12
         *
         *  @param   event       e: an event object. This can be ignored
         *  @param   Element     postbox: An element which contains the new HTML
         *
         *  @return  n/a
         */

        $(document).live('acf/setup_fields', function(e, postbox) {

            $(postbox).find('.field[data-field_type="focuspoint"]').each(function() {

                initialize_field($(this));

            });

        });

    }

    // Initialize dynamic block preview (editor).
    if (window.acf) {
        //window.acf.addAction('render_block_preview/type=focuspoint', initialize_field);
        window.acf.addAction('render_block_preview/type=focuspoint', acf.fields.focuspoint.initialize);
    }


})(jQuery);