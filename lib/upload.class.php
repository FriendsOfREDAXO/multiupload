<?php

if(!class_exists('rex_mediapool_multiupload')) {

    class rex_mediapool_multiupload {

        private $addon;
        public $addon_name = null;
        public $sync_cat = null;
        public $clear_uploadlist_automatically = null;
        public $clear_file_after_finish = null;
        public $upload_simultaneously = null;
        public $javascript_debug = null;
        public $showFootnote = null;
        public $return_markup = true;
        public $onUploadCallback = null;
        public $onSubmitCallback = null;
        public $onProgressCallback = null;
        public $onCompleteCallback = null;
        public $onCancelCallback = null;
        public $mediaCats = null;
        public $time = null;

        function __construct(){
            $this->addon_name = "multiupload";
            $this->addon = rex_addon::get('multiupload');
            $this->sync_cat = $this->addon->getProperty("sync_cats");
            $this->clear_uploadlist_automatically = $this->addon->getProperty("clear_uploadlist_automatically");
            $this->clear_file_after_finish = $this->addon->getProperty("clear_file_after_finish");
            $this->upload_simultaneously = $this->addon->getProperty("upload_simultaneously");
            $this->javascript_debug = $this->addon->getProperty("javascript_debug");
            $this->showFootnote = $this->addon->getProperty("show_footnote");
            $this->folder = $this->addon->getProperty("folder");
            $this->markup = $this->return_markup;
            $this->time = uniqid();
        }


        /**
        * setValue() function to edit all parameters
        */
        public function setValue($sync = true, $clear_auto = true, $clear_after_finish = true, $simultan_uploads_value = 5, $js_debug = false, $footnote = true, $folder = ""){

            $this->sync_cat = $sync;
            $this->clear_uploadlist_automatically = $clear_auto;
            $this->clear_file_after_finish = $clear_after_finish;
            $this->upload_simultaneously = $simultan_uploads_value;
            $this->javascript_debug = $js_debug;
            $this->showFootnote = $footnote;
            $this->folder = $folder;
        }

        /**
        * setter function for displaying catsync select (boolean: true/false)
        */
        public function setSyncCat($value = true) {
            $this->sync_cat = $value;
        }

        /**
        * setter function for auto clear list (boolean: true/false)
        */
        public function setClearUploadsAutomatically($value = true){
            $this->clear_uploadlist_automatically = $value;
        }

        /**
        * setter function for auto clear file after complete/failure (boolean: true/false)
        */
        public function setClearFileAfterFinish($value = true){
            $this->clear_file_after_finish = $value;
        }

        /**
        * setter function for simultan uploads (int)
        */
        public function setSimultanUploads($value = 5){
            if(is_numeric($value)){
                $this->upload_simultaneously = $value;
            } else {
                $this->upload_simultaneously = 5;
            }
        }

        /**
        * setter function for activating js debug (boolean: true/false)
        */
        public function setJSDebug($value = false){
            $this->javascript_debug = $value;
        }

        /**
        * setter function for displaying footnote (boolean: true/false)
        */
        public function setFootnote($value = true){
            $this->showFootnote = $value;
        }

        /**
        * setter function for returning multiupload with markup (boolean: true/false)
        */
        public function setMarkup($return_markup = true) {
            $this->markup = $return_markup;
        }

        /**
        * function to register javascript callbacks / $fn needs to be a simple "function" without ()
        */
        public function setCallback($type, $fn = null){
            switch ($type) {
                case "upload":
                $this->onUploadCallback = $fn;
                break;

                case "submit":
                $this->onSubmitCallback = $fn;
                break;

                case "progress":
                $this->onProgressCallback = $fn;
                break;

                case "complete":
                $this->onCompleteCallback = $fn;
                break;

                case "cancel":
                $this->onCancelCallback = $fn;
                break;
            }
        }


        /**
        * getter function - returns mediaSync select
        */
        public function getMediaCats(){
            $rex_file_category = rex_request('rex_file_category', 'int');

            // include cat sync select
            $cats_sel = new rex_media_category_select;
            $cats_sel->setStyle('class="form-control"');
            $cats_sel->setSize(1);
            $cats_sel->setName('rex_file_category');
            $cats_sel->setId('rex_file_category_'.$this->time);
            $cats_sel->addOption(rex_i18n::msg('pool_kats_no'),"0");
            $cats_sel->setSelected($rex_file_category);
            return $cats_sel->get();
        }


        /**
        * creates and returns the uploadform
        */
        public function createUploadForm() {

            $rex_file_category = rex_request('rex_file_category', 'int');


            $output = '';
            $script_page_header = '';
            $uploadPath = "index.php?page=".$this->addon_name."/upload&upload_folder=".$this->folder;




            if($this->sync_cat){

                $cats_sel = new rex_media_category_select();
                $cats_sel->setStyle('class="form-control"');
                $cats_sel->setSize(1);
                $cats_sel->setName('rex_file_category');
                $cats_sel->setId('rex_file_category_'.$this->time);
                $cats_sel->addOption(rex_i18n::msg('pool_kats_no'), '0');
                $cats_sel->setAttribute('onchange', 'this.form.submit()');
                $cats_sel->setSelected($rex_file_category);

                $arg_fields = '';
                foreach (rex_request('args', 'array') as $arg_name => $arg_value) {
                    $arg_fields .= '<input type="hidden" name="args[' . $arg_name . ']" value="' . $arg_value . '" />' . "\n";
                }

                $opener_input_field = rex_request('opener_input_field', 'string');
                if ($opener_input_field != '') {
                    $arg_fields .= '<input type="hidden" name="opener_input_field" value="' . htmlspecialchars($opener_input_field) . '" />' . "\n";
                }


                $panel = '';
                $formElements = [];

                if($this->markup)
                {
                    $e = [];
                    $e['label'] = '<label for="rex_file_category_'.$this->time.'">' . rex_i18n::msg('pool_file_category') . '</label>';
                    $e['field'] = $cats_sel->get();
                    $formElements[] = $e;

                    $fragment = new rex_fragment();
                    $fragment->setVar('elements', $formElements, false);
                    $panel .= $fragment->parse('core/form/form.php');

                    $panel .= rex_extension::registerPoint(new rex_extension_point('MEDIA_FORM_ADD', ''));

                    $fragment = new rex_fragment();
                    $fragment->setVar('class', 'info', false);
                    $fragment->setVar('title', "Medienpool Kategorie");
                    $fragment->setVar('body', $panel, false);
                    $content = $fragment->parse('core/page/section.php');

                    $output .= '
                    <form action="' . rex_url::currentBackendPage() . '" method="post" enctype="multipart/form-data">
                    <fieldset>
                      <input type="hidden" name="media_method" value="add_file" />
                        ' . $arg_fields . '
                        ' . $content . '
                    </fieldset></form>';
                }
            } # end sync_cat


            $output_upload_button = '';
            $output_upload_button .= '
                <div id="multiupload'.$this->time.'" class="'.($this->markup ? 'behave_normal' : 'styleless').'">
                    <noscript>      
                        <p>'.rex_i18n::msg('multiupload_uploadform_js_info').'</p>
                    </noscript>
                </div>';


            if($this->markup){
                if(!$this->clear_uploadlist_automatically) {
                    $content = '';
                    $content .= $output_upload_button;
                    $content .= '
                        <p>'.rex_i18n::rawMsg('multiupload_uploadform_remove_label').'</p>
                            <a href="javascript:void(0)" onclick="clearUploadList();">'.rex_i18n::rawMsg('multiupload_uploadform_remove_button').'</a>
                        </p>';

                    $sections = '';
                    $fragment = new rex_fragment();
                    $fragment->setVar('class', 'info', false);
                    $fragment->setVar('title', "Aktionen");
                    $fragment->setVar('body', $content, false);
                    $output .= $fragment->parse('core/page/section.php');
                }
            }


            $script_page_header .= '
            <script type="text/javascript">
              
              function rex_multiupload_createUploader'.$this->time.'(){            
                var uploader = new qq.FileUploader({
                  element: document.getElementById("multiupload'.$this->time.'"),
                  action: "'.$uploadPath.'",
                  mediaPoolSelector: "rex_file_category_'.$this->time.'",
                  sizeLimit: 0, // max size   
                  minSizeLimit: 0, // min size';

            $script_page_header .= '
                onSubmit: function(id,filename) {'."\n";

            if($this->clear_uploadlist_automatically) {
                $script_page_header .= '
                clearUploadList();';
            }

            if($this->onSubmitCallback){
                $script_page_header .= '
                if(typeof '.$this->onSubmitCallback.' == "function") { 
                    // user callback function
                    '.$this->onSubmitCallback.'(filename);
                }';
            }

            $script_page_header .= '
            },'; # end onSubmit

            $script_page_header .= '
                onUpload: function(id,fileName, xhr) {'."\n";

            if($this->onUploadCallback){
                $script_page_header .= '
                if(typeof '.$this->onUploadCallback.' == "function") { 
                    // user callback function
                    '.$this->onUploadCallback.'(fileName, xhr);
                }';
            }

            $script_page_header .= '
            },'; # end onUpload

            $script_page_header .= '
                onProgress: function(id,fileName, loaded, total) {'."\n";

            if($this->onProgressCallback){
                $script_page_header .= '
                if(typeof '.$this->onProgressCallback.' == "function") { 
                    // user callback function
                    '.$this->onProgressCallback.'(fileName, loaded, total);
                }';
            }

            $script_page_header .= '
            },'; # end onProgress

            $script_page_header .= '
                onComplete: function(id,filename,json) {'."\n";

            if($this->clear_file_after_finish)
            {
                $script_page_header .= '                        
                    window.setTimeout(function(){
                        clearUploadListSuccess();
                    }, 5000);';
            }

            if($this->onCompleteCallback){
                $script_page_header .= '
                if(typeof '.$this->onCompleteCallback.' == "function" && json.success) { 
                    // user callback function
                    '.$this->onCompleteCallback.'(json);
                }';
            }

            $script_page_header .= '
            },'; # end onComplete

            $script_page_header .= '
                  onCancel: function(id,filename) {'."\n";

            if($this->onCancelCallback){
                $script_page_header .= '
                if(typeof '.$this->onCancelCallback.' == "function") { 
                    // user callback function
                    '.$this->onCancelCallback.'(filename);
                }';
            }

            $script_page_header .= '
            },'; # end onCancel

            if($this->upload_simultaneously && is_numeric($this->upload_simultaneously)){
                $script_page_header .= ' 
                    maxConnections: '.$this->upload_simultaneously.',';
            }

            $script_page_header .= '
                    debug: '.($this->javascript_debug ? "true" : "false").'
                });
            }
            jQuery(document).ready(function(){
                rex_multiupload_createUploader'.$this->time.'();
            });
            </script>'."\n";


            if($this->markup){
                if($this->showFootnote){
                    $output .=
                        '<div class="rex-form-row edit_panel">
                            <label>'.rex_i18n::rawMsg('multiupload_uploadform_list_label').'</label>
                            <ul class="qq-upload-list edit_uploads"></ul>
                        </div>'."\n";
                }
            }

            if($this->markup){
                if($this->showFootnote){
                    $output .=
                        '<section class="rex-page-section">
                            <p class="rex-form-file">
                                <span class="rex-form-notice">'.rex_i18n::rawMsg('multiupload_uploadform_footnote').'</span>
                            </p>
                        </section>'."\n";
                }
            }

            rex_extension::register('OUTPUT_FILTER', function(rex_extension_point $ep) use ($script_page_header){
                $suchmuster = "<!-- ###MULTIUPLOAD_EP_REPLACE### -->";
                $ersetzen = "<!-- MULTIUPLOAD_EP_REPLACE -->\n".$script_page_header."<!-- /MULTIUPLOAD_EP_REPLACE -->\n</head>";
                $ep->setSubject(str_replace($suchmuster, $ersetzen, $ep->getSubject()));
            });


            // TIME FOR OUTPUT
            return $output;
        } # end createUploadForm
    } # end rex_mediapool_multiupload
} # end class_exists
