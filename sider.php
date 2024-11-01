<?php
class QrcodeSider extends Qrcode_Widget {
	function w_id(){ return 'qrcode_activation'; }

	function w_name(){ return __("Two Dimensional Code"); }

	function w_defaults(){
        return array(
            'title' => __("Two Dimensional Code"),
            'sites' => 'qrcode',
            'hmargin' => '4',
            'customcss' => '',
            'iconsperrow' => '10',
            'rowsvmargin' => '10',
            'i18nsite' => 'Site',
            'i18npage' => 'Page',
            'i18npost' => 'Post',
			'description' => __("Show the page of two-dimensional code."),
        );
    }

	function w_form($instance){
        $rv='';
        $rv.=   '<p>'.$this->w_form_inputset($instance, 'title', __("Title:")).'</p>';
        return $rv;
    }

	/** Title %type substitution */
	function w_title($instance){
        extract($instance);

		if (!empty($title)){
			if (strpos($title, '%type')>=0){
                $d=$this->w_defaults();
                $i18nsite=isset($i18nsite) && !empty($i18nsite) ? $i18nsite : $d['i18nsite'];
                $i18npost=isset($i18npost) && !empty($i18npost) ? $i18npost : $d['i18npost'];
                $i18npage=isset($i18npage) && !empty($i18npage) ? $i18npage : $d['i18npage'];

				$type=(is_home() || is_front_page()?$i18nsite:(is_single()?$i18npost:$i18npage));
				$title=str_replace('%type', $type, $title);
			}
		}
		return $title;
	}

    /** Return the real widget content */
    function w_content($instance){
        extract($instance);
        $d=$this->w_defaults();

        $sites=explode(",", $sites);
        $nsites=count($sites);
        $iconsadded=0;

        $iconsperrow=(int)(isset($iconsperrow) && !empty($iconsperrow)?$iconsperrow:$d['iconsperrow']);
        $rowsvmargin=isset($rowsvmargin) && !empty($rowsvmargin)?$rowsvmargin:$d['rowsvmargin'];

        for ($i=0; $i<$nsites; $i++){
			$site=strtolower(trim($sites[$i]));
			if (method_exists($this, $site.'Button')){
                //Don't insert margin for last button
                $firsticon=$iconsadded===0;
                $lasticon = ($i+1)==$nsites;
                $lastrowicon=$lasticon || ($iconsadded>0 && ( ($iconsadded+1) % $iconsperrow)===0);
                $firstnewrowicon=$iconsadded>0 && ($iconsadded % $iconsperrow)===0;
                $st=''; //Button Styles
                //Every row has its own div
                if ($firstnewrowicon){
                    $rv.='</div>';
                    //New row, add an empty separator div
                    $rv.='<div class="strx-simple-sharing-sidebar-rows-separator" style="height:'.$rowsvmargin.'px;"></div>';
                    //Force the next icon to go to down
                    $st.='clear: both; ';
                }
                if ($firsticon || $firstnewrowicon){
                   $rv.='<div class="strx-simple-sharing-sidebar-buttons">';
                }

                if ( !($lasticon || $lastrowicon) ){
                    $st.='margin-right:'.$hmargin.'px; ';
                }

				$rv.='<div style="'.$st.' width:100%;" class="strx-simple-sharing-sidebar-button strx-simple-sharing-sidebar-'.$site.'-button">'.
						$this->{$site.'Button'}().
					'</div>';
                $iconsadded++;
			}
		}
		$rv.='</div>';

        return $rv;
    }

	function qrcodeButton(){
		$qrcode_img = qrcode_get_filename_by_url();
		if(file_exists(TWO_DIMENSIONAL_DIR . QRCODE_PATH . $qrcode_file)){
			//return '<figure><img src="' . TWO_DIMENSIONAL_URL . QRCODE_PATH . $qrcode_img.'" alt="'.__("Mobile two-dimensional code scanning").'" /><figcaption>'.__("Scanning two-dimensional code to view this article").'</figcaption></figure>';
			return '<img src="' . TWO_DIMENSIONAL_URL . QRCODE_PATH . $qrcode_img.'" alt="'.__("Mobile two-dimensional code scanning").'" />';
		}else{
			return '<p style="text-align:center;">'.__("No two-dimensional code has been generated").'</p>';
		}
	}

	function currentUrl(){
		$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
		$host     = $_SERVER['HTTP_HOST'];
		$script   = $_SERVER['SCRIPT_NAME'];
		$params   = $_SERVER['QUERY_STRING'];
		$currentUrl = $protocol . '://' . $host . $script;// . '?' . $params;
      	return $currentUrl;
	}
}

// end sider.php