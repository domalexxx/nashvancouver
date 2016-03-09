<?php

add_action('init', 'bsaStartSession', 1);
function bsaStartSession()
{
	if(!session_id()) {
		session_start();
	}
}

// -- START -- Marketing Agency Functions
function bsa_role()
{
	if ( current_user_can('manage_option') || current_user_can('install_plugins') ) {
		return 'admin';
	} else {
		return 'user';
	}
}

function bsa_verify_role($id, $type)
{
	$model = new BSA_PRO_Model();
	$user_info = get_userdata(get_current_user_id());

	if ( bsa_role() == 'admin' ) {
		return TRUE;
	} else {
		if ( $type == 'site' ) {
			if ( bsa_site($id, 'user_id') == get_current_user_id() ) {
				return TRUE;
			} else {
				return FALSE;
			}
		} elseif ( $type == 'space' ) {
			if ( bsa_space($id, 'site_id') != NULL && strpos($model->getUserSites('id', bsa_role()), bsa_space($id, 'site_id')) !== FALSE ) {
				return TRUE;
			} else {
				return FALSE;
			}
		} elseif ( $type == 'ad' ) {
			if ( bsa_ad($id, 'space_id') != NULL && strpos($model->getUserSpaces(), bsa_ad($id, 'space_id')) !== FALSE ) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}

function bsa_site($id, $column = NULL)
{
	$model = new BSA_PRO_Model();
	$get_site = $model->getSite($id);
	$params = explode(',', $column);

	foreach ( $params as $param ) {
		if ( isset($_SESSION['bsa_site_'.$id][$param]) && $_SESSION['bsa_site_'.$id][$param] != '' ) {
			return $_SESSION['bsa_site_'.$id][$param];
		} else {
			if ( $param != NULL ) {
				if ( $get_site[$param] ) {
					$_SESSION['bsa_site_'.$id][$param] = $get_site[$param];
					return $get_site[$param];
				} else {
					return NULL;
				}
			} else {
				if ( $get_site ) {
					$_SESSION['bsa_site_'.$id]['id'] = $get_site['id'];
					return $get_site['id'];
				} else {
					return NULL;
				}
			}
		}
	}
}
// -- END -- Marketing Agency Functions

//function bsa_space($id, $column = NULL)
//{
//	$model = new BSA_PRO_Model();
//	$get_space = $model->getSpace($id);
//	$params = explode(',', $column);
//
//	foreach ( $params as $param ) {
//		if ( isset($_SESSION['bsa_space_'.$id][$param]) && $_SESSION['bsa_space_'.$id][$param] != '' ) {
//			return $_SESSION['bsa_space_'.$id][$param];
//		} else {
//			if ( $param != NULL ) {
//				if ( $get_space[$param] ) {
//					$_SESSION['bsa_space_'.$id][$param] = $get_space[$param];
//					return $get_space[$param];
//				} else {
//					return NULL;
//				}
//			} else {
//				if ( $get_space ) {
//					$_SESSION['bsa_space_'.$id]['id'] = $get_space['id'];
//					return $get_space['id'];
//				} else {
//					return NULL;
//				}
//			}
//		}
//	}
//}
//
//function get_bsa_ads()
//{
//	$model = new BSA_PRO_Model();
//	$get_ads = $model->getAds();
//
//	return $get_ads;
//}
//
//function bsa_ad($id, $column = NULL)
//{
//	$model = new BSA_PRO_Model();
//	$get_ad = $model->getAd($id);
//	$params = explode(',', $column);
//
//	foreach ( $params as $param ) {
//		if ( isset($_SESSION['bsa_ad_'.$id][$param]) && $_SESSION['bsa_ad_'.$id][$param] != '' ) {
//			return $_SESSION['bsa_ad_'.$id][$param];
//		} else {
//			if ( $param != NULL ) {
//				if ( $get_ad[$param] ) {
//					$_SESSION['bsa_ad_'.$id][$param] = $get_ad[$param];
//					return $get_ad[$param];
//				} else {
//					return NULL;
//				}
//			} else {
//				if ( $get_ad ) {
//					$_SESSION['bsa_ad_'.$id]['id'] = $get_ad['id'];
//					return $get_ad['id'];
//				} else {
//					return NULL;
//				}
//			}
//		}
//	}
//}

function bsa_get_opt($var, $str)
{
	$get = get_option('bsa_pro_plugin_' . $var);
	if ( isset($get) && isset($get[$str]) ) {
		return $get[$str];
	} else {
		return null;
	}
}

function bsa_get_trans($var, $str)
{
	$get = get_option('bsa_pro_plugin_trans_' . $var);
	if ( isset($get) && isset($get[$str]) ) {
		return $get[$str];
	} else {
		return null;
	}
}

function bsa_space($id, $column = NULL)
{
	$params = explode(',', $column);

	if ( $params != null ) {
		foreach ( $params as $param ) {
			if ( $param != '' ) {
				if ( isset($_SESSION['bsa_space_'.$id][$param]) && $_SESSION['bsa_space_'.$id][$param] != '' ) {
					return $_SESSION['bsa_space_'.$id][$param];
				} else {
					$model = new BSA_PRO_Model();
					$get_space = $model->getSpace($id);
					if ( $param != null ) {
						if ( isset($get_space[$param]) ) {
							$_SESSION['bsa_space_'.$id][$param] = $get_space[$param];
							return $get_space[$param];
						} else {
							return null;
						}
					} else {
						if ( isset($get_space) ) {
							$_SESSION['bsa_space_'.$id]['id'] = $get_space['id'];
							return $get_space['id'];
						} else {
							return null;
						}
					}
				}
			} else {
				$model = new BSA_PRO_Model();
				$get_space = $model->getSpace($id);
				if ( isset($get_space) ) {
					$_SESSION['bsa_space_'.$id]['id'] = $get_space['id'];
					return $get_space['id'];
				} else {
					return null;
				}
			}
		}
	} else {
		$model = new BSA_PRO_Model();
		$get_space = $model->getSpace($id);
		if ( isset($get_space) ) {
			$_SESSION['bsa_space_'.$id]['id'] = $get_space['id'];
			return $get_space['id'];
		} else {
			return null;
		}
	}
}

function get_bsa_ads()
{
	$model = new BSA_PRO_Model();
	$get_ads = $model->getAds();

	return $get_ads;
}

function bsa_ad($id, $column = NULL)
{
	$params = explode(',', $column);

	if ( $params != null ) {
		foreach ( $params as $param ) {
			if ( $param != '' ) {
				if ( isset($_SESSION['bsa_ad_'.$id][$param]) && $_SESSION['bsa_ad_'.$id][$param] != '' ) {
					return $_SESSION['bsa_ad_'.$id][$param];
				} else {
					$model = new BSA_PRO_Model();
					$get_ad = $model->getAd($id);
					if ( $param != null ) {
						if ( isset($get_ad[$param]) ) {
							$_SESSION['bsa_ad_'.$id][$param] = $get_ad[$param];
							return $get_ad[$param];
						} else {
							return null;
						}
					} else {
						if ( isset($get_ad) ) {
							$_SESSION['bsa_ad_'.$id]['id'] = $get_ad['id'];
							return $get_ad['id'];
						} else {
							return null;
						}
					}
				}
			} else {
				$model = new BSA_PRO_Model();
				$get_ad = $model->getAd($id);
				if ( isset($get_ad) ) {
					$_SESSION['bsa_ad_'.$id]['id'] = $get_ad['id'];
					return $get_ad['id'];
				} else {
					return null;
				}
			}
		}
	} else {
		$model = new BSA_PRO_Model();
		$get_ad = $model->getAd($id);
		if ( isset($get_ad) ) {
			$_SESSION['bsa_ad_'.$id]['id'] = $get_ad['id'];
			return $get_ad['id'];
		} else {
			return null;
		}
	}
}

// generate form url
function bsaFormURL($sid, $type = null)
{
	$ofu = get_option('bsa_pro_plugin_ordering_form_url');
	$mfu = get_site_option('bsa_pro_plugin_order_form_url');
	$oau = get_option('bsa_pro_plugin_agency_ordering_form_url');
	$mau = get_site_option('bsa_pro_plugin_agency_order_form_url');
	$form_url = ( ( isset($type) && $type == 'agency') ? ((is_multisite()) ? $mau : $oau) : ((is_multisite()) ? $mfu : $ofu ) );

	return $form_url.(( strpos($form_url, '?') == TRUE ) ? '&' : '?').'sid='.$sid.(bsa_space($sid, 'site_id') != '' ? '&site_id='.bsa_space($sid, 'site_id') : '');
}

function bsaGetExampleAd($template, $edit = null)
{
	if ( isset($edit) ) {
		$ad = array(
			array(
				"template" => $template,
				"id" => 0,
				"title" => get_option('bsa_pro_plugin_trans_example_title'),
				"description" => get_option('bsa_pro_plugin_trans_example_desc'),
				"url" => get_option('bsa_pro_plugin_trans_example_url'),
				"img" => plugins_url('/bsa-pro-scripteo/frontend/img/example.jpg')
			)
		);
	} else {
		if ( isset($_POST['bsa_ad_id']) ) {
			$ad = array(
				array(
					"template" => $template,
					"id" => 0,
					"title" => bsa_ad($_POST['bsa_ad_id'], "title"),
					"description" => bsa_ad($_POST['bsa_ad_id'], "description"),
					"url" => bsa_ad($_POST['bsa_ad_id'], "url"),
					"img" => bsa_ad($_POST['bsa_ad_id'], "img")
				)
			);
		} else {
			$ad = null;
		}
	}

	return $ad;
}

function bsaCreateCustomAdTemplates()
{
	$custom_templates = get_option('bsa_pro_plugin_custom_templates');
	if ( $custom_templates ) {
		$custom_templates = explode(',', $custom_templates);
		foreach ( $custom_templates as $custom_template ) {
			if ( $custom_template != '' ) {
				$template = explode('--', $custom_template);
				$width = $template[0];
				$height = $template[1];
				bsaCreateAdTemplate($width, $height);
			}
		}
	}
}

function bsaGetPost($name)
{
	if (isset($_POST[$name])) {
		return $_POST[$name];
	} else {
		return '';
	}
}

function bsa_crop_tool($crop = null, $img_url = null, $width = null, $height = null)
{
	if ( $img_url != null ) {
		if ( $crop == 'yes' && $width != null && $height != null ) {
			return bfi_thumb($img_url, array('width' => $width, 'height' => $height, 'crop' => true));
		} else {
			return $img_url;
		}
	} else {
		return plugins_url('/bsa-pro-scripteo/frontend/img/example.jpg');
	}
}

function bsa_column_exists($table, $column)
{
	$model = new BSA_PRO_Model();
	$if_exists = $model->columnExists($table, $column);

	if ( $if_exists != FALSE ) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function bsa_option_exists($id, $table, $column)
{
	if ( isset($id) && $id != '' && isset($table) && $table != '' && isset($column) && $column != '' ) {

		if ( $table == 'sites' ) {
			if ( bsa_site($id, $column) != NULL || bsa_site($id, $column) != '' ) {
				return TRUE;
			} else {
				return FALSE;
			}
		} elseif ( $table == 'spaces' ) {
			if ( bsa_space($id, $column) != NULL || bsa_space($id, $column) != '' ) {
				return TRUE;
			} else {
				return FALSE;
			}
		} elseif ( $table == 'ads' ) {
			if ( bsa_ad($id, $column) != NULL || bsa_ad($id, $column) != '' ) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}

function bsa_counter($id, $type)
{
	$model = new BSA_PRO_Model();
	$get_counter = $model->getCounter($id, $type);

	if ( $get_counter ) {
		return $get_counter;
	} else {
		return NULL;
	}
}

add_filter( 'the_content', 'bsa_load_ads_in_content' );
function bsa_load_ads_in_content($content) {
	if ( get_option('bsa_pro_plugin_before_hook') != '' && get_option('bsa_pro_plugin_before_hook') != null || get_option('bsa_pro_plugin_after_hook') != '' && get_option('bsa_pro_plugin_after_hook') != null ) {
		$get_before 		= explode(';', get_option('bsa_pro_plugin_before_hook'));
		$get_after 			= explode(';', get_option('bsa_pro_plugin_after_hook'));
		$before_content 	= null;
		$after_content 		= null;

		if ( isset($get_before) ) {
			foreach ( $get_before as $before ) {
				$before_content 	.= do_shortcode($before);
			}
		}
		if ( isset($get_after) ) {
			foreach ( $get_after as $after ) {
				$after_content 	.= do_shortcode($after);
			}
		}
		return $before_content.$content.$after_content;
	} else {
		return $content;
	}
}

add_filter( 'the_content', 'bsa_load_ads_after_paragraphs' );
function bsa_load_ads_after_paragraphs( $content ) {
	$p_tag				= '</p>';
	$paragraphs = explode( $p_tag, $content );
	foreach ($paragraphs as $key => $paragraph) {
		for ( $i = 1; $i <= 7; $i++ ) {
			$after_paragraph 	= $i;
			if ( get_option('bsa_pro_plugin_after_' . $i . '_paragraph') != '' && get_option('bsa_pro_plugin_after_' . $i . '_paragraph') != null ) {
				$get_after = explode(';', get_option('bsa_pro_plugin_after_' . $i . '_paragraph'));
				foreach ( $get_after as $after ) {
					if ( trim( $paragraph ) ) {
						$paragraphs[$key] .= $p_tag;
					}
					if ( $after_paragraph == $key + 1 ) {
						$paragraphs[$key] .= do_shortcode($after);
					}
				}
			}
		}
	}
	return implode( '', $paragraphs );
}

function bsa_number_format($number)
{
	// default format
	$format = ((get_option('bsa_pro_plugin_currency_format')) ? explode('|', get_option('bsa_pro_plugin_currency_format')) : array(2, '.', ' '));

	// if new
	if (isset($_GET['bsa_currency_format'])) {
		update_option('bsa_pro_plugin_currency_format', $_GET['bsa_currency_format']);
		$format = explode('|', $_GET['bsa_currency_format']);
	}
	$number = (isset($number) && $number > 0 ? $number : 0);

	return number_format($number, $format[0], $format[1], $format[2]);
}

function bsa_get_user_geo_data()
{
	if ( session_id() ) {
		if ( isset($_SESSION['bsaProGeoUser']) ) {
			return $_SESSION['bsaProGeoUser'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
			$response = wp_remote_get('http://ip-api.com/php/'.$ip);

			if( is_array($response) ) {
				$getGeoData = @unserialize($response['body']);
				if ( isset($getGeoData) ) {
					$_SESSION['bsaProGeoUser'] = $getGeoData;
					return $getGeoData;
				} else {
					return 'no_code';
				}
			} else {
				return 'no_code';
			}
		}
	} else {
		return 'no_code';
	}
}

function bsa_pro_verify_device($space_id)
{
	$detect = new BSA_Mobile_Detect();

	if ( isset($space_id) && bsa_space($space_id, 'devices') != '' && bsa_space($space_id, 'devices') != null && bsa_space($space_id, 'devices') != 'mobile,tablet,desktop' ) {

		if( !$detect->isMobile() && !$detect->isTablet() && in_array('desktop', explode(',', bsa_space($space_id, 'devices')), false) === true || // If desktop device.
			$detect->isTablet() && in_array('tablet', explode(',', bsa_space($space_id, 'devices')), false) === true || // If tablet device.
			$detect->isMobile() && !$detect->isTablet() && in_array('mobile', explode(',', bsa_space($space_id, 'devices')), false) === true ) { // If mobile device.

			if ( !$detect->isMobile() && !$detect->isTablet() ) {
//				echo 'desktop';
				if ( in_array('desktop', explode(',', bsa_space($space_id, 'devices')), false) === true ) {
					return true;
				} else {
					return false;
				}
			} elseif ( $detect->isTablet() ) {
//				echo 'tablet';
				if ( in_array('tablet', explode(',', bsa_space($space_id, 'devices')), false) === true ) {
					return true;
				} else {
					return false;
				}
			} elseif ( $detect->isMobile() && !$detect->isTablet() ) {
//				echo 'mobile';
				if ( in_array('mobile', explode(',', bsa_space($space_id, 'devices')), false) === true ) {
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}

		} else {
			return false;
		}

	} else {
		return true;
	}
}

function bsa_pro_verify_geo($type, $countries)
{
	if ( $type != null && $countries != null && $countries != '' ) {
		$get_user_data = bsa_get_user_geo_data();
		if (
			isset($get_user_data) || $get_user_data == 'no_code' ) {

			if ( $type == 'show' && $countries != null && $countries != '' || $type == 'hide' && $countries != null && $countries != '' ) {
				if ($type == 'show' && in_array($get_user_data['countryCode'], explode(',', $countries), false) === true || // valid countries
					$type == 'hide' && in_array($get_user_data['countryCode'], explode(',', $countries), false) !== true) { // valid countries
					return true;
				} else {
					return false;
				}
			}

			if ( $type == 'show_advanced' && $countries != null && $countries != '' ) {
				if ($type == 'show_advanced' && in_array($get_user_data['regionName'], explode(',', $countries), false) === true || // valid region
					$type == 'show_advanced' && in_array($get_user_data['city'], explode(',', $countries), false) === true || // valid cities
					$type == 'show_advanced' && in_array($get_user_data['zip'], explode(',', $countries), false) === true) { // valid zip
					return true;
				} else {
					return false;
				}
			}

			if ( $type == 'hide_advanced' && $countries != null && $countries != '' ) {
				if ($type == 'hide_advanced' && in_array($get_user_data['regionName'], explode(',', $countries), false) !== true && // valid region
					$type == 'hide_advanced' && in_array($get_user_data['city'], explode(',', $countries), false) !== true && // valid cities
					$type == 'hide_advanced' && in_array($get_user_data['zip'], explode(',', $countries), false) !== true) { // valid zip
					return true;
				} else {
					return false;
				}
			}

			return true;
		} else {
			return false;
		}
	} else {
		return true;
	}
}

// get close actions
function bsaGetCloseActions($sid, $type)
{
	if ( bsa_space($sid, 'close_action') != null && bsa_space($sid, 'close_action') != '' ) {
		$get_close_action = explode(',', bsa_space($sid, 'close_action'));
		if ( $type == 'show_ads' ) {
			if ( isset($get_close_action[0]) ) {
				return number_format($get_close_action[0], 0, '', '');
			} else {
				return 0;
			}
		} elseif ( $type == 'show_close_btn' ) {
			if ( isset($get_close_action[1]) ) {
				return number_format($get_close_action[1], 0, '', '');
			} else {
				return 0;
			}
		} elseif ( $type == 'close_ads' ) {
			if ( isset($get_close_action[2]) ) {
				return number_format($get_close_action[2], 0, '', '');
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}

// capping function - capped ads
function bsaGetCappedAds($sid)
{
	$model 			= new BSA_PRO_Model();
	$capped_ads 	= (isset($_SESSION['bsa_capped_ads_'.$sid]) ? $_SESSION['bsa_capped_ads_'.$sid] : null);
	$ads 			= $model->getActiveAds($sid, bsa_space($sid, 'max_items'), null, '0'.$capped_ads);

	foreach ( $ads as $ad ) {
		$aid 			= $ad['id'];
		$ad_capping 	= bsa_ad($aid, 'capping');
		$sessionAdCapping 	= (isset($_SESSION['capped_ad_'.$aid]) ? $_SESSION['capped_ad_'.$aid] : null);

		if ( !isset($sessionAdCapping) ) {
			$_SESSION['capped_ad_'.$aid] = $ad_capping;
		}
	}

	foreach ( $ads as $ad ) {
		$aid 			= $ad['id'];
		$ad_capping 	= bsa_ad($aid, 'capping');

		if ( $ad_capping != null && $ad_capping != '' && $ad_capping > 0 ) { // if capping isset
			$sessionAdCapping 	= (isset($_SESSION['capped_ad_'.$aid]) ? $_SESSION['capped_ad_'.$aid] : null);

//			var_dump($sessionAdCapping);
			if ( !isset($sessionAdCapping) ) {
				$_SESSION['capped_ad_'.$aid] = $ad_capping;
			} else {
				if ( $sessionAdCapping > 0 ) {
					$_SESSION['capped_ad_'.$aid] = $sessionAdCapping - 1;
				} else {
					$capped_ads .= (strpos($capped_ads, ','.$aid) !== false) ? null : ','.$aid;
				}
			}
		}
	}
//	var_dump($capped_ads);

	if ( $capped_ads ) {
		$_SESSION['bsa_capped_ads_'.$sid] = $capped_ads;
		return $capped_ads;
	} else {
		return null;
	}
}

function bsa_pro_ad_space($space_id = null, $max_width = null, $delay = null, $padding_top = null, $attachment = null, $crop = null, $hide_for_id = null, $if_empty = null)
{
	if ( $space_id == null ) {
		echo "<strong>ADS Error</strong> Missing <strong>id</strong> parameter!";
		return '';
	} else {
		$show_in_country = bsa_space($space_id, 'show_in_country');
		$hide_in_country = bsa_space($space_id, 'hide_in_country');
		$show_in_advanced = bsa_space($space_id, 'show_in_advanced');
		$hide_in_advanced = bsa_space($space_id, 'hide_in_advanced');

		if (get_option('bsa_pro_plugin_' . 'hide_if_logged') != 'yes' && is_user_logged_in() && in_array(get_the_ID(), explode(',', $hide_for_id), false) !== true && // Hide for logged users or specific pages
			bsa_pro_verify_geo('show', $show_in_country) && bsa_pro_verify_geo('hide', $hide_in_country) && // Show / Hide in Countries
			bsa_pro_verify_geo('show_advanced', $show_in_advanced) && bsa_pro_verify_geo('hide_advanced', $hide_in_advanced) && // Show / Hide in Regions, Cities, ZipCodes
			bsa_pro_verify_device($space_id) || // Verify device
			!is_user_logged_in() && in_array(get_the_ID(), explode(',', $hide_for_id), false) !== true && // Hide for logged users or specific pages
			bsa_pro_verify_geo('show', $show_in_country) && bsa_pro_verify_geo('hide', $hide_in_country) && // Show / Hide in Countries
			bsa_pro_verify_geo('show_advanced', $show_in_advanced) && bsa_pro_verify_geo('hide_advanced', $hide_in_advanced) && // Show / Hide in Regions, Cities, ZipCodes
			bsa_pro_verify_device($space_id) // Verify device
		) {

			// Rand Space ID
			$space_ids = explode(',', $space_id);
			$space_rand_id = array_rand($space_ids, 1);
			$space_id = $space_ids[$space_rand_id];

			// if in category or has tag
			if (bsa_space($space_id, 'id') && bsa_space($space_id, 'in_categories') != '' && bsa_space($space_id, 'in_categories') != null ||
				bsa_space($space_id, 'id') && bsa_space($space_id, 'has_tags') != '' && bsa_space($space_id, 'has_tags') != null
			) {
				$get_categories = bsa_space($space_id, 'in_categories');
				$get_tags = bsa_space($space_id, 'has_tags');
				$exp_categories = explode(',', $get_categories);
				$exp_tags = explode(',', $get_tags);

				$taxonomy_cat = 'empty';
				$taxonomy_tag = 'empty';
				if (is_array($exp_categories)) {
					foreach ($exp_categories as $category) {
						if (in_category($category)) {
							$taxonomy_cat = 'isset';
							break;
						}
					}
				}
				if (is_array($exp_tags)) {
					foreach ($exp_tags as $tag) {
						if (has_tag($tag)) {
							$taxonomy_tag = 'isset';
							break;
						}
					}
				}
				if ($get_categories == '' && $get_categories == null && $get_tags == '' && $get_tags == null ||
					$get_categories == '' && $get_categories == null && $taxonomy_tag == 'isset' ||
					$taxonomy_cat == 'isset' && $get_tags == '' && $get_tags == null ||
					$taxonomy_cat == 'isset' && $taxonomy_tag == 'isset'
				) {
					$taxonomy = 'isset';
				} else {
					$taxonomy = 'empty';
				}
			} else {
				$taxonomy = 'none';
			}

			if (bsa_space($space_id, 'id') && bsa_space($space_id, 'status') == 'active' && $taxonomy == 'isset' ||
				bsa_space($space_id, 'id') && bsa_space($space_id, 'status') == 'active' && $taxonomy == 'none'
			) {

				if (glob(plugin_dir_path(__FILE__) . "../frontend/template/" . bsa_space($space_id, 'template') . ".php") == null) {
					$styleName = 'default';
				} else {
					$styleName = bsa_space($space_id, 'template');
				}

				$sid 		= $space_id;
				$model 		= new BSA_PRO_Model();
				$ads 		= $model->getActiveAds($sid, bsa_space($sid, 'max_items'), null, '0'.bsaGetCappedAds($sid));
				$type 		= (bsa_space($sid, 'site_id') != NULL) ? 'agency' : null;
				$crop 		= ($crop == 'no') ? 'no' : null;

				if ( defined('W3TC') && !isset($if_empty) ) { // w3 total cache
					if ( !defined('W3TC_DYNAMIC_SECURITY') ) {
						define('W3TC_DYNAMIC_SECURITY', md5(rand(0, 99999)));
					}
					echo "<!--mfunc <?php echo W3TC_DYNAMIC_SECURITY; ?> -->";
					$ads 		= $model->getActiveAds($sid, bsa_space($sid, 'max_items'), null, '0'.bsaGetCappedAds($sid));
					echo "<!--/mfunc <?php echo W3TC_DYNAMIC_SECURITY; ?> -->";
				}

				$example = null;
				if ( !isset($if_empty) && get_option('bsa_pro_plugin_example_ad') == 'yes' ) { // example ads if empty ad space
					$example = true;
				}

				if (count($ads) > 0 || count($ads) == 0 && !isset($if_empty) ) {
					if (isset($sid) && bsa_space($sid, 'display_type') == 'corner') {
						echo '<div class="bsaProCorner bsaProCorner-' . $sid . '">'; // -- START -- CORNER
						echo '
				<div class="bsaProRibbon"></div>
					<div class="bsaProCornerContent">
						<div class="bsaProCornerInner">';
					} elseif (isset($sid) && bsa_space($sid, 'display_type') == 'floating' ||
						isset($sid) && bsa_space($sid, 'display_type') == 'floating-bottom-right' ||
						isset($sid) && bsa_space($sid, 'display_type') == 'floating-bottom-left' ||
						isset($sid) && bsa_space($sid, 'display_type') == 'floating-top-left' ||
						isset($sid) && bsa_space($sid, 'display_type') == 'floating-top-right'
					) {
						echo '<div class="bsaProFloating bsaProFloating-' . $sid . '" style="display: none"><div class="bsaFloatingButton"><span class="bsaFloatingClose bsaFloatingClose-' . $sid . '"></span></div>'; // -- START -- FLOATING
					} elseif (isset($sid) && bsa_space($sid, 'display_type') == 'carousel') {
						echo '<div class="bsaProCarousel bsaProCarousel-' . $sid . '">'; // -- START -- CAROUSEL
					} elseif (isset($sid) && bsa_space($sid, 'display_type') == 'top_scroll_bar' || isset($sid) && bsa_space($sid, 'display_type') == 'bottom_scroll_bar') {
						echo '<div class="bsaProScrollBar bsaProScrollBar-' . $sid . '">'; // -- START -- TOP / BOTTOM SCROLL BAR
						if (bsa_space($sid, 'display_type') == 'bottom_scroll_bar') {
							echo '<div class="bsaProScrollBarButton"><span class="bsaProScrollBarClose bsaProScrollBarClose-' . $sid . '"></span></div>';
						}
					} elseif (isset($sid) && bsa_space($sid, 'display_type') == 'popup' ||
						isset($sid) && bsa_space($sid, 'display_type') == 'exit_popup'
					) {
						echo '
					<div class="bsaPopupWrapperBg bsaPopupWrapperBg-' . $sid . ' bsaHidden" style="display:none"></div>

					<div class="bsaPopupWrapper bsaPopupWrapper-' . $sid . ' bsaHidden" style="display:none">

						<div class="bsaPopupWrapperInner">
				'; // -- START -- POPUP, EXIT POPUP
					} elseif (isset($sid) && bsa_space($sid, 'display_type') == 'layer') {
						echo '
					<div class="bsaPopupWrapperBg bsaPopupWrapperBg-' . $sid . ' bsaHidden"></div>

					<div class="bsaPopupWrapper bsaPopupWrapper-' . $sid . ' bsaHidden">
				'; // -- START -- LAYER
					}

					// -- START -- DEFAULT
					require dirname(__FILE__) . '/../frontend/template/' . $styleName . '.php';
					// -- END -- DEFAULT

					if (isset($sid) && bsa_space($sid, 'display_type') != 'popup' &&
						bsa_space($sid, 'display_type') != 'corner' &&
						bsa_space($sid, 'display_type') != 'layer' &&
						bsa_space($sid, 'display_type') != 'exit_popup' &&
						bsa_space($sid, 'display_type') != 'background' &&
						bsa_space($sid, 'display_type') != 'link') {
						if ( bsaGetCloseActions($sid, 'show_ads') > 0 ): ?>
							<style>
								.bsaProContainer-<?php echo $sid?> {
									display: none;
								}
							</style>
						<?php endif; ?>
						<script>
							(function ($) {
								var bsaProContainer = $('.bsaProContainer-<?php echo $sid?>');
								var number_show_ads = "<?php echo bsaGetCloseActions($sid, 'show_ads') ?>";
								var number_hide_ads = "<?php echo bsaGetCloseActions($sid, 'close_ads') ?>";
								if ( number_show_ads > 0 ) {
									setTimeout(function () { bsaProContainer.fadeIn(); }, number_show_ads * 1000);
								}
								if ( number_hide_ads > 0 ) {
									setTimeout(function () { bsaProContainer.fadeOut(); }, number_hide_ads * 1000);
								}
							})(jQuery);
						</script>
					<?php
					}

					if (isset($sid) && bsa_space($sid, 'display_type') == 'background') {
						?>
						<style>
							body {
								background-position: top center !important;
								background-color: #e6e6e6 !important;
								background-repeat: no-repeat !important;
								background-attachment: <?php echo ((isset($attachment) && $attachment == 'scroll') ? 'scroll' : 'fixed' ) ?> !important;
								padding-top: <?php echo ((isset($padding_top) && $padding_top != '') ? $padding_top.'px' : 'inherit') ?> !important;
							}
						</style>
						<script>
							(function ($) {
								$(document).ready(function () {
									var body = "body";
									var getImage = $(".bsaProContainer-<?php echo $sid ?> .bsaProItemInner__img").css("background-image");
									var getUrl = $(".bsaProContainer-<?php echo $sid ?> .bsaProItem__url").attr('href');
									$(".bsaProContainer-<?php echo $sid ?>").hide();
									$(body).css("background-image", getImage);
									$(body).click(function (e) {
										var body_target = $(e.target);
										if (body_target.is(body) == true) {
											window.open(getUrl, "_blank");
										}
									});

									$(document).mousemove(function (e) {
										var body_target = $(e.target);
										if (body_target.is(body)) {
											body_target.css("cursor", "pointer");
										} else {
											$(body).css("cursor", "auto");
										}
									});
								});
							})(jQuery);
						</script>
					<?php
					} elseif (isset($sid) && bsa_space($sid, 'display_type') == 'corner') {
						echo '
						</div>
					</div>
				</div>'; // -- END -- CORNER
						?>
						<script>
							(function ($) {
								var body = $(document);
								$(window).scroll(function () {
									if ($(window).scrollTop() >= (body.height() - (body.height() - (body.height() * (<?php echo (($delay != 0 && $delay != NULL) ? $delay : $delay) / 100 ?>)))) - $(window).height()) {
										setTimeout(function () {
											<?php if ( bsaGetCloseActions($sid, 'show_ads') == 0 ): ?>
											$(".bsaProCorner-<?php echo $sid ?>").fadeIn();
											<?php endif; ?>
										}, 400);
									}
								});
								var number_show = "<?php echo bsaGetCloseActions($sid, 'show_ads') ?>";
								var number_close = "<?php echo bsaGetCloseActions($sid, 'close_ads') ?>";
								if ( number_show > 0 ) {
									setTimeout(function () { bsaProCorner.fadeIn(400); }, number_show * 1000);
								}
								if ( number_close > 0 ) {
									setTimeout(function () { bsaProCorner.fadeOut(400); }, number_close * 1000);
								}
								var bsaProCorner = $(".bsaProCorner-<?php echo $sid ?>");
								bsaProCorner.appendTo(document.body);
							})(jQuery);
						</script>
						<style>
							.bsaProCorner-<?php echo $sid ?> {
								display: <?php echo (bsaGetCloseActions($sid, 'show_ads') > 0) ? 'none' : 'block' ?>;
								position: fixed;
								width: 150px;
								height: 150px;
								z-index: 10000;
								top: <?php echo (( is_user_logged_in() ) ? '32px' : '0') ?>;
								right: 0;
								-webkit-transition: all .5s; /* Safari */
								transition: all .5s;
							}
							.bsaProCorner:hover {
								width: 250px;
								height: 250px;
							}
						</style>
					<?php
					} elseif (isset($sid) && bsa_space($sid, 'display_type') == 'floating' ||
						isset($sid) && bsa_space($sid, 'display_type') == 'floating-bottom-right' ||
						isset($sid) && bsa_space($sid, 'display_type') == 'floating-bottom-left' ||
						isset($sid) && bsa_space($sid, 'display_type') == 'floating-top-left' ||
						isset($sid) && bsa_space($sid, 'display_type') == 'floating-top-right'
					) {
						echo '</div>'; // -- END -- FLOATING
						?>
						<script>
							(function ($) {
								var body = $(document);
								$(window).scroll(function () {
									if ($(window).scrollTop() >= (body.height() - (body.height() - (body.height() * (<?php echo (($delay != 0 && $delay != NULL) ? $delay : $delay) / 100 ?>)))) - $(window).height()) {
										setTimeout(function () {
											$(".bsaProFloating-<?php echo $sid ?>").fadeIn();
										}, 400);
									}
								});
								var bsaProFloating = $(".bsaProFloating-<?php echo $sid ?>");
								var bsaFloatingClose = $(".bsaFloatingClose-<?php echo $sid ?>");
								bsaProFloating.appendTo(document.body);
								bsaFloatingClose.click(function () {
									setTimeout(function () {
										bsaProFloating.removeClass("zoomInDown").addClass("animated zoomOutUp");
									}, 400);
								});
								var number_close = "<?php echo bsaGetCloseActions($sid, 'close_ads') ?>";
								var number_show = "<?php echo bsaGetCloseActions($sid, 'show_close_btn') ?>";
								if ( number_close > 0 ) {
									setTimeout(function () { bsaProFloating.fadeOut(400); }, number_close * 1000);
									setTimeout(function () { bsaProFloating.remove(); }, (number_close * 1000) + 400);
								}
								if ( number_show > 0 ) {
									bsaFloatingClose.hide();
									setTimeout(function () {
										bsaFloatingClose.fadeIn();
									}, number_show * 1000);
								}
							})(jQuery);
						</script>
						<style>
							.bsaProFloating-<?php echo $sid ?> {
								position: fixed;
								max-width: <?php echo (($max_width != 0 && $max_width != NULL) ? $max_width : '320') ?>px;
								width: 90%;
								z-index: 10000;
							<?php if ( bsa_space($sid, 'display_type') == 'floating-top-left' ) {
									echo '
										top: '.(( is_user_logged_in() ) ? 47 : 15).'px;
										left: 15px;
									';
								} elseif ( bsa_space($sid, 'display_type') == 'floating-top-right' ) {
									echo '
										top: '.(( is_user_logged_in() ) ? 47 : 15).'px;
										right: 15px;
									';
								} elseif ( bsa_space($sid, 'display_type') == 'floating-bottom-left' ) {
									echo '
										bottom: '.(( is_user_logged_in() ) ? 47 : 15).'px;
										left: 15px;
									';
								} else {
									echo '
										bottom: '.(( is_user_logged_in() ) ? 47 : 15).'px;
										right: 15px;
									';
								}
							?>
							}

							<?php if ( bsa_space($sid, 'display_type') == 'floating-top-left' || bsa_space($sid, 'display_type') == 'floating-bottom-left' ) {
									echo '
										.bsaProFloating-'.$sid.' .bsaFloatingButton {
											float: left;
										}
									';
								}
							?>
						</style>
					<?php
					} elseif (isset($sid) && bsa_space($sid, 'display_type') == 'carousel') {
					echo '</div>'; // -- END -- CAROUSEL
					?>
					<script>
						(function ($) {
							$(document).ready(function () {
								setTimeout(function () {
									var owl = $(".bsa-owl-carousel-<?php echo $sid; ?>");
									owl.owlCarousel({
										autoPlay: <?php echo (($delay != 0 && $delay != NULL) ? $delay : '2') * 1000 ?>,
										autoWidth: true,
										slideSpeed: 400,
										paginationSpeed: 700,
										rewindSpeed: 1000,
										singleItem: true,
										stopOnHover: true,
										items: 1,
										responsive:{
											0:{
												items:1
											}
										}
									});
								}, 10);
							});
							window.addEventListener("orientationchange", function() {
								setTimeout(function () {
									var owl = $(".bsa-owl-carousel-<?php echo $sid; ?>");
									owl.data('owlCarousel').reinit();
								}, 200);
							}, false);
						})(jQuery);
					</script>
					<style>
						.bsaProCarousel-<?php echo $sid?> {
							max-width: <?php echo (($max_width != 0 && $max_width != NULL) ? $max_width : '728') ?>px;
							width: 100%;
							overflow: hidden;
						}
					</style>
				<?php
				} elseif (isset($sid) && bsa_space($sid, 'display_type') == 'top_scroll_bar' || isset($sid) && bsa_space($sid, 'display_type') == 'bottom_scroll_bar') {
					if (bsa_space($sid, 'display_type') == 'top_scroll_bar') {
						echo '<div class="bsaProScrollBarButton"><span class="bsaProScrollBarClose bsaProScrollBarClose-' . $sid . '"></span></div>';
					}
					echo '</div>'; // -- END -- TOP / BOTTOM SCROLL BAR
					?>
					<script>
						(function ($) {
							$(document).ready(function () {
								var bsaScrollBarWrapper = $('.bsaProScrollBar-<?php echo $sid?>');
								var bsaScrollBarInner = $('.bsaProScrollBar-<?php echo $sid?> .bsaProContainer-<?php echo $sid?> .bsaProItems');
								var bsaScrollBarClose = $(".bsaProScrollBarClose-<?php echo $sid ?>");
								bsaScrollBarWrapper.appendTo(document.body);
								bsaScrollBarInner.simplyScroll({
									speed: 2
								});
								bsaScrollBarClose.click(function () {
									setTimeout(function () {
										bsaScrollBarWrapper.removeClass("zoomInDown").addClass("animated zoomOutUp");
									}, 400);
								});
								var number_close = "<?php echo bsaGetCloseActions($sid, 'close_ads') ?>";
								var number_show = "<?php echo bsaGetCloseActions($sid, 'show_close_btn') ?>";
								if ( number_close > 0 ) {
									setTimeout(function () { bsaScrollBarWrapper.fadeOut(400); }, number_close * 1000);
									setTimeout(function () { bsaScrollBarWrapper.remove(); }, (number_close * 1000) + 400);
								}
								if ( number_show > 0 ) {
									bsaScrollBarClose.hide();
									setTimeout(function () {
										bsaScrollBarClose.fadeIn();
									}, number_show * 1000);
								}
							});
						})(jQuery);
					</script>
					<style>
						.bsaProScrollBar-<?php echo $sid?> {
							width: 100%;
							position: fixed;
						<?php if ( bsa_space($sid, 'display_type') == 'top_scroll_bar' ): ?> top: <?php echo (( is_user_logged_in() ) ? '32px' : '0') ?>;
						<?php else: ?> bottom: 0;
						<?php endif; ?> left: 0;
							z-index: 10000;
						}
						.bsaProScrollBar-<?php echo $sid?> .bsaProItem {
							margin: 0 !important;
						}
						.bsaProScrollBar-<?php echo $sid?>, .bsaProScrollBar-<?php echo $sid?> .bsaProItems, .bsaProScrollBar-<?php echo $sid?> .bsaProContainer .bsaProItem.bsaReset {
							clear: none;
						}
						/* Explicitly set height/width of each list item */
						.simply-scroll .simply-scroll-list .bsaProItem {
							float: left; /* Horizontal scroll only */
							width: <?php echo (($max_width != 0 && $max_width != NULL) ? $max_width.'px' : 1920 / bsa_space($sid, 'col_per_row')).'px' ?> !important;
							height: auto;
						}
					</style>
				<?php
				} elseif (isset($sid) && bsa_space($sid, 'display_type') == 'popup') {
					echo '</div><span class="bsaPopupClose bsaPopupClose-' . $sid . '"></span>';
					echo '</div>'; // -- END -- POPUP
					?>
					<script>
						(function ($) {
							var bsaPopupWrapperBg = $(".bsaPopupWrapperBg-<?php echo $sid?>");
							var bsaPopupWrapper = $(".bsaPopupWrapper-<?php echo $sid?>");
							var bsaBody = $("body");
							if (bsaPopupWrapper.hasClass('bsaClosed') == false) {
								setTimeout(function () {
									bsaBody.css({
										"overflow": "hidden",
										"height": ( bsaBody.hasClass("logged-in") ) ? $(window).height() - 32 : $(window).height()
									});
									bsaPopupWrapper.appendTo(document.body).removeClass("bsaHidden").addClass("animated fadeIn").fadeIn();
									bsaPopupWrapperBg.appendTo(document.body).removeClass("bsaHidden").addClass("animated fadeIn").fadeIn();
								}, <?php echo bsaGetCloseActions($sid, 'show_ads') * 1000 ?>);
							}
							$(document).ready(function () {
								var bsaPopupClose = $(".bsaPopupClose-<?php echo $sid ?>");
								bsaPopupClose.click(function () {
									bsaBody.css({"overflow": "visible", "height": "auto"});
									bsaPopupClose.addClass("animated zoomOut");
									bsaPopupWrapper.removeClass("fadeIn").addClass("animated fadeOut bsaClosed").fadeOut();
									bsaPopupWrapperBg.removeClass("fadeIn").addClass("animated fadeOut").fadeOut();
								});
								var number_close = "<?php echo bsaGetCloseActions($sid, 'close_ads') ?>";
								var number_show = "<?php echo bsaGetCloseActions($sid, 'show_close_btn') ?>";
								if ( number_close > 0 ) {
									setTimeout(function () {
//										bsaPopupWrapperBg.fadeOut();
//										bsaPopupWrapper.fadeOut();
//										setTimeout(function () { bsaPopupWrapperBg.remove(); bsaPopupWrapper.remove(); }, 1000);
									}, number_close * 1000);
								}
								if ( number_show > 0 ) {
									bsaPopupClose.hide();
									setTimeout(function () {
										bsaPopupClose.fadeIn();
									}, number_show * 1000);
								}
							});
						})(jQuery);
					</script>
					<?php if (bsa_option_exists($sid, 'spaces', 'ad_extra_color_1') || $max_width != ''): ?>
						<style>
							<?php if ($max_width != ''): ?>
							.bsaPopupWrapper-<?php echo $sid ?> .bsaProContainer {
								max-width: <?php echo (($max_width != 0 && $max_width != NULL) ? $max_width.'px' : '100%') ?> !important;
								margin: 0 auto;
							}
							<?php endif; ?>
							<?php if (bsa_option_exists($sid, 'spaces', 'ad_extra_color_1')): ?>
							.bsaPopupWrapper-<?php echo $sid ?> {
								background-color: <?php echo bsa_space($sid, 'ad_extra_color_1')?>;
							}
							<?php endif; ?>
						</style>
					<?php endif; ?>
				<?php
				} elseif (isset($sid) && bsa_space($sid, 'display_type') == 'layer') {
					echo '<span class="bsaPopupClose bsaPopupClose-' . $sid . '"></span>';
					echo '</div>'; // -- END -- LAYER
					?>
					<script>
						(function ($) {
							var bsaPopupWrapperBg = $(".bsaPopupWrapperBg-<?php echo $sid ?>");
							var bsaPopupWrapper = $(".bsaPopupWrapper-<?php echo $sid ?>");
							var bsaBody = $("body");
							setTimeout(function () {
								var getImage = $(".bsaProContainer-<?php echo $sid ?> .bsaProItemInner__img").css("background-image");
								$(".bsaProContainer-<?php echo $sid ?>").hide();
								bsaBody.css({
									"overflow": "hidden",
									"height": ( bsaBody.hasClass("logged-in") ) ? $(window).height() - 32 : $(window).height()
								});
								bsaPopupWrapper.css("background-image", getImage).appendTo(document.body).removeClass("bsaHidden").addClass("animated fadeIn").fadeIn();
								bsaPopupWrapperBg.appendTo(document.body).removeClass("bsaHidden").addClass("animated fadeIn").fadeIn();
							}, <?php echo bsaGetCloseActions($sid, 'show_ads') * 1000 ?>);
							$(document).ready(function () {
								var bsaPopupClose = $(".bsaPopupClose-<?php echo $sid ?>");
								bsaPopupClose.click(function () {
									bsaBody.css({"overflow": "visible", "height": "auto"});
									bsaPopupClose.addClass("animated zoomOut");
									bsaPopupWrapper.removeClass("fadeIn").addClass("animated fadeOut").fadeOut();
									bsaPopupWrapperBg.removeClass("fadeIn").addClass("animated fadeOut").fadeOut();
								});
								var getUrl = $(".bsaProContainer-<?php echo $sid ?> .bsaProItem__url").attr('href');
								$(bsaPopupWrapper).click(function (e) {
									var layer_target = $(e.target);
									if (layer_target.is(bsaPopupWrapper) == true) {
										window.open(getUrl, "_blank");
									}
								});
								$(document).mousemove(function (e) {
									var layer_target = $(e.target);
									if (layer_target.is(bsaPopupWrapper)) {
										layer_target.css("cursor", "pointer");
									} else {
										$(bsaPopupWrapper).css("cursor", "auto");
									}
								});
								var number_close = "<?php echo bsaGetCloseActions($sid, 'close_ads') ?>";
								var number_show = "<?php echo bsaGetCloseActions($sid, 'show_close_btn') ?>";
								if ( number_close > 0 ) {
									setTimeout(function () {
										bsaPopupWrapperBg.fadeOut(400);
										bsaPopupWrapper.fadeOut(400);
										setTimeout(function () { bsaPopupWrapperBg.remove(); bsaPopupWrapper.remove(); }, 400);
									}, number_close * 1000);
								}
								if ( number_show > 0 ) {
									bsaPopupClose.hide();
									setTimeout(function () {
										bsaPopupClose.fadeIn();
									}, number_show * 1000);
								}
							});
						})(jQuery);
					</script>
				<?php
				} elseif (isset($sid) && bsa_space($sid, 'display_type') == 'exit_popup') {
					echo '</div><span class="bsaPopupClose bsaPopupClose-' . $sid . '"></span>';
					echo '</div>'; // -- END -- EXIT POPUP
					?>
					<script>
						(function ($) {
							var isDesktop = (function () {
								return !('ontouchstart' in window) || !('onmsgesturechange' in window);
							})();
							window.isDesktop = isDesktop;
							if (isDesktop) {
								var bsaPopupWrapperBg = $(".bsaPopupWrapperBg-<?php echo $sid ?>");
								var bsaPopupWrapper = $(".bsaPopupWrapper-<?php echo $sid ?>");
								var bsaBody = $("body");
								$(document).ready(function () {
									var bsaPopupClose = $(".bsaPopupClose-<?php echo $sid ?>");
									bsaPopupClose.click(function () {
										bsaBody.css({"overflow": "visible", "height": "auto"});
										bsaPopupClose.addClass("animated zoomOut");
										bsaPopupWrapper.removeClass("fadeIn").addClass("animated fadeOut").fadeOut();
										bsaPopupWrapperBg.removeClass("fadeIn").addClass("animated fadeOut").fadeOut();
									});
								});
								$(document).bind("mouseleave", function () {
									if (bsaPopupWrapper.hasClass('fadeIn') == false && bsaPopupWrapper.hasClass('bsaClosed') == false) {
										bsaBody.css({
											"overflow": "hidden",
											"height": ( bsaBody.hasClass("logged-in") ) ? $(window).height() - 32 : $(window).height()
										});
										bsaPopupWrapper.appendTo(document.body).removeClass("bsaHidden").addClass("animated fadeIn bsaClosed").fadeIn();
										bsaPopupWrapperBg.appendTo(document.body).removeClass("bsaHidden").addClass("animated fadeIn").fadeIn();
									}
								});
							}
						})(jQuery);
					</script>
					<style>
						.bsaPopupWrapper-<?php echo $sid ?> .bsaProContainer {
							max-width: <?php echo (($max_width != 0 && $max_width != NULL) ? $max_width.'px' : '100%') ?>;
							margin: 0 auto;
						}
						.bsaPopupWrapper-<?php echo $sid ?> {
							background-color: <?php echo bsa_space($sid, 'ad_extra_color_1')?>;
						}
					</style>
				<?php
				}
				return '';
			}
		} else {
			return null;
		}
	}
}
return '';
}

function bsa_upload_url($type = 'baseurl')
{
	if ( is_multisite() ) {
		$upload_basedir = get_site_option('bsa_pro_plugin_main_basedir');
		$upload_baseurl = get_site_option('bsa_pro_plugin_main_baseurl');
	} else {
		$upload_dir = wp_upload_dir();
		$upload_basedir = $upload_dir['basedir'];
		$upload_baseurl = $upload_dir['baseurl'];
	}
	if ( $type == 'basedir' )
		$upload_path = $upload_basedir.'/bsa-pro-upload/';
	else
		$upload_path = $upload_baseurl.'/bsa-pro-upload/';

	if( ! file_exists( $upload_path ) )
		wp_mkdir_p( $upload_path );

	if ( is_ssl() )
		$upload_path = str_replace( 'http://', 'https://', $upload_path );

	return $upload_path;
}

add_shortcode( 'bsa_pro_ad_space', 'create_bsa_pro_short_code_space' );
function create_bsa_pro_short_code_space( $atts, $content = null )
{
	$a = shortcode_atts( array(
		'id' 				=> $atts['id'],
		'max_width' 		=> ( isset($atts['max_width']) ) ? $atts['max_width'] : '',
		'delay' 			=> ( isset($atts['delay']) ) ? $atts['delay'] : '',
		'padding_top' 		=> ( isset($atts['padding_top']) ) ? $atts['padding_top'] : '',
		'attachment' 		=> ( isset($atts['attachment']) ) ? $atts['attachment'] : '',
		'crop' 				=> ( isset($atts['crop']) ) ? $atts['crop'] : '',
		'hide_for_id' 		=> ( isset($atts['hide_for_id']) ) ? $atts['hide_for_id'] : '',
		'if_empty' 			=> ( isset($atts['if_empty']) ) ? $atts['if_empty'] : null,
	), $atts );

	ob_start();
	if ( get_option('bsa_pro_plugin_'.'hide_if_logged') != 'yes' && is_user_logged_in() && in_array(get_the_ID(), explode(',', $a['hide_for_id']), false) !== true || !is_user_logged_in() && in_array(get_the_ID(), explode(',', $a['hide_for_id']), false) !== true ) { // Hide for logged users or specific pages
		if ($content != null && bsa_space($a['id'], 'display_type') == 'link') {
			?>
			<style>
				.bsaProLink-<?php echo $a['id'] ?> .bsaProLinkHover-<?php echo $a['id'] ?> {
					left: 0;
					width: <?php echo $a['max_width'].'px' ?>;
				}
			</style>
			<?php
			echo '<div class="bsaProLink bsaProLink-' . $a['id'] . '">' . $content . '<div class="bsaProLinkHover bsaProLinkHover-' . $a['id'] . '">';
		}

		$ad_space_1 = bsa_pro_ad_space($a['id'], $a['max_width'], $a['delay'], $a['padding_top'], $a['attachment'], $a['crop'], $a['hide_for_id'], $a['if_empty']);
		$ad_space_2 = null;
		if ( isset($a['if_empty']) )
			$ad_space_2 = bsa_pro_ad_space($a['if_empty'], $a['max_width'], $a['delay'], $a['padding_top'], $a['attachment'], $a['crop'], $a['hide_for_id']);

		if ( $ad_space_1 != null )
			bsa_pro_ad_space($a['id'], $a['max_width'], $a['delay'], $a['padding_top'], $a['attachment'], $a['crop'], $a['hide_for_id'], $a['if_empty']);
		elseif ( $ad_space_2 != null )
			bsa_pro_ad_space($a['if_empty'], $a['max_width'], $a['delay'], $a['padding_top'], $a['attachment'], $a['crop'], $a['hide_for_id']);

		//bsa_pro_ad_space($a['id'], $a['max_width'], $a['delay'], $a['padding_top'], $a['attachment'], $a['crop'], $a['hide_for_id']);

		if ($content != null && bsa_space($a['id'], 'display_type') == 'link') {
			echo '</div></div>';
		}
	}
	return ob_get_clean();
}

//add_shortcode( 'bsa_pro_ajax_ad_space', 'create_bsa_pro_ajax_short_code_space' );
//function create_bsa_pro_ajax_short_code_space( $atts, $content = null )
//{
//	$a = shortcode_atts( array(
//		'id' 			=> $atts['id'],
//		'max_width' 	=> ( isset($atts['max_width']) ) ? $atts['max_width'] : '',
//		'delay' 		=> ( isset($atts['delay']) ) ? $atts['delay'] : '',
//		'position' 		=> ( isset($atts['position']) ) ? $atts['position'] : '',
//		'padding_top' 	=> ( isset($atts['padding_top']) ) ? $atts['padding_top'] : '',
//		'attachment' 	=> ( isset($atts['attachment']) ) ? $atts['attachment'] : '',
//		'crop' 			=> ( isset($atts['crop']) ) ? $atts['crop'] : '',
//		'hide_for_id' 	=> ( isset($atts['hide_for_id']) ) ? $atts['hide_for_id'] : '',
//	), $atts );
//
//	ob_start();
//	echo '<div class="bsa_ajax_load">';
//
//	echo '</div>';
//	echo '
//	<script>
//	(function($) {
//		var bsaLoaderInputs = $(".bsaLoaderInputs");
//		bsaLoaderInputs.fadeIn(400);
//		$.post("'.admin_url("admin-ajax.php").'", {action:"bsa_ajax_load_ad_spaces",bsa_space_id:"'.$a["id"].'",bsa_pro_content:"'.$content.'"}, function(result) {
//			$(".bsa_ajax_load").html(result);
//			console.log(result);
//		});
//	})(jQuery);
//	</script>
//	';
//	return ob_get_clean();
//}

add_shortcode( 'bsa_pro_form_and_stats', 'create_bsa_pro_short_code_form_and_stats' );
function create_bsa_pro_short_code_form_and_stats()
{
	ob_start();
	if ( isset($_GET['bsa_pro_stats']) && isset($_GET['bsa_pro_id']) && isset($_GET['bsa_pro_email']) && bsa_ad($_GET['bsa_pro_id'], 'buyer_email') == $_GET['bsa_pro_email'] ) {
		require dirname(__FILE__) . '/BSA_PRO_Stats.php';
	} else {
		require dirname(__FILE__) . '/BSA_PRO_Ordering_form.php';
	}
	return ob_get_clean();
}

add_shortcode( 'bsa_pro_agency_form', 'create_bsa_pro_short_code_agency_form' );
function create_bsa_pro_short_code_agency_form()
{
	ob_start();
	if ( isset($_GET['bsa_pro_stats']) && isset($_GET['bsa_pro_id']) && isset($_GET['bsa_pro_email']) && bsa_ad($_GET['bsa_pro_id'], 'buyer_email') == $_GET['bsa_pro_email'] ) {
		require dirname(__FILE__) . '/BSA_PRO_Agency_Stats.php';
	} else {
		require dirname(__FILE__) . '/BSA_PRO_Agency_Ordering_form.php';
	}
	return ob_get_clean();
}

add_action( 'wp', 'bsa_pro_wp_redirect' );
function bsa_pro_wp_redirect() {
	if ( isset( $_GET['bsa_pro_url'] ) && isset( $_GET['bsa_pro_id'] ) ) {
		$model = new BSA_PRO_Model();
		wp_redirect( $model->bsaProCounter() );
		exit;
	}
}

add_action( 'vc_before_init', 'ads_pro_plugin_ad_space' );
function ads_pro_plugin_ad_space() {
	vc_map( array(
		"name" => __( "ADS PRO", "my-text-domain" ),
		"base" => "ads_pro_ad_space",
		"class" => "",
		"icon" => plugins_url('../frontend/img/small-logo.png', __FILE__),
		"category" => __( "Content", "my-text-domain"),
		'admin_enqueue_js' => "",
		'admin_enqueue_css' => "",
		"params" => array(
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __( "Space ID", "my-text-domain" ),
				"param_name" => "id",
				"value" => __( "1", "my-text-domain" ),
				"description" => __( "Enter Space ID here.", "my-text-domain" )
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __( "Max width", "my-text-domain" ),
				"param_name" => "max_width",
				"value" => __( NULL, "my-text-domain" ),
				"description" => __( "Max width of ad space in pixels, eg. 468", "my-text-domain" )
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __( "Delay", "my-text-domain" ),
				"param_name" => "delay",
				"value" => __( NULL, "my-text-domain" ),
				"description" => __( "Param in seconds for a popup & slider ads, eg. 3", "my-text-domain" )
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __( "Padding top", "my-text-domain" ),
				"param_name" => "padding_top",
				"value" => __( NULL, "my-text-domain" ),
				"description" => __( "Param in pixels for a background ads, eg. 100", "my-text-domain" )
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __( "Attachment", "my-text-domain" ),
				"param_name" => "attachment",
				"value" => __( NULL, "my-text-domain" ),
				"description" => __( "Param for a background ads, eg. scroll or fixed", "my-text-domain" )
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __( "Crop", "my-text-domain" ),
				"param_name" => "crop",
				"value" => __( NULL, "my-text-domain" ),
				"description" => __( "If you do not want to use cropping for images, enter 'no'", "my-text-domain" )
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __( "Hide for ID", "my-text-domain" ),
				"param_name" => "hide_for_id",
				"value" => __( NULL, "my-text-domain" ),
				"description" => __( "Hide Ad Space for ID e.g. 3,10,100", "my-text-domain" )
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __( "Show other Ad Space if empty", "my-text-domain" ),
				"param_name" => "if_empty",
				"value" => __( NULL, "my-text-domain" ),
				"description" => __( "Show other Ad Space if empty e.g. 2", "my-text-domain" )
			),
			array(
				"type" => "textarea",
				"holder" => "",
				"class" => "",
				"heading" => __( "Content", "my-text-domain" ),
				"param_name" => "content", // Important: Only one textarea_html param per content element allowed and it should have "content" as a "param_name"
				"value" => __( NULL, "my-text-domain" ),
				"description" => __( "Enter your content.", "my-text-domain" )
			)
		)
	) );
}

add_shortcode( 'ads_pro_ad_space', 'ads_pro_ad_space_function' );
function ads_pro_ad_space_function( $atts, $content = null )
{
	extract( shortcode_atts( array(
		'id' 				=> $atts['id'],
		'max_width' 		=> ( isset($atts['max_width']) ) ? $atts['max_width'] : null,
		'delay' 			=> ( isset($atts['delay']) ) ? $atts['delay'] : null,
		'padding_top' 		=> ( isset($atts['padding_top']) ) ? $atts['padding_top'] : null,
		'attachment' 		=> ( isset($atts['attachment']) ) ? $atts['attachment'] : null,
		'crop' 				=> ( isset($atts['crop']) ) ? $atts['crop'] : null,
		'hide_for_id' 		=> ( isset($atts['hide_for_id']) ) ? $atts['hide_for_id'] : null,
		'if_empty' 			=> ( isset($atts['if_empty']) ) ? $atts['if_empty'] : null,
	), $atts ) );

	$content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content

	$id 				= "{$id}";
	$max_width 			= "{$max_width}";
	$delay 				= "{$delay}";
	$padding_top 		= "{$padding_top}";
	$attachment 		= "{$attachment}";
	$crop 				= "{$crop}";
	$hide_for_id 		= "{$hide_for_id}";
	$if_empty 			= "{$if_empty}";

	ob_start();
	if ( get_option('bsa_pro_plugin_'.'hide_if_logged') != 'yes' && is_user_logged_in() && in_array(get_the_ID(), explode(',', $hide_for_id), false) !== true || !is_user_logged_in() && in_array(get_the_ID(), explode(',', $hide_for_id), false) !== true ) { // Hide for logged users or specific pages
		if ( $content != null && bsa_space($id, 'display_type') == 'link' ) {
			?>
			<style>
				.bsaProLink-<?php echo $id ?> .bsaProLinkHover-<?php echo $id ?> {
					left: 0;
					width: <?php echo $max_width.'px' ?>;
				}
			</style>
			<?php
			echo '<div class="bsaProLink bsaProLink-'.$id.'">'.$content.'<div class="bsaProLinkHover bsaProLinkHover-'.$id.'">';
		}

		$ad_space_1 = bsa_pro_ad_space($id, $max_width, $delay, $padding_top, $attachment, $crop, $hide_for_id, $if_empty);
		$ad_space_2 = null;
		if ( isset($if_empty) )
			$ad_space_2 = bsa_pro_ad_space($if_empty, $max_width, $delay, $padding_top, $attachment, $crop, $hide_for_id);

		if ( $ad_space_1 != null )
			bsa_pro_ad_space($id, $max_width, $delay, $padding_top, $attachment, $crop, $hide_for_id, $if_empty);
		elseif ( $ad_space_2 != null )
			bsa_pro_ad_space($if_empty, $max_width, $delay, $padding_top, $attachment, $crop, $hide_for_id);

//		bsa_pro_ad_space($id, $max_width, $delay, $padding_top, $attachment, $crop, $hide_for_id);

		if ( $content != null && bsa_space($id, 'display_type') == 'link' ) {
			echo '</div></div>';
		}
	}
	return ob_get_clean();
}

// AdBlock Detection Shortcode
add_shortcode( 'bsa_pro_adblock_notice', 'bsa_pro_adblock_notice_function' );
function bsa_pro_adblock_notice_function( $atts )
{
	extract( shortcode_atts( array(
		'message' 	=> (isset($atts['message']) ? $atts['message'] : '<h3>Page blocked!</h3><p>Please disable <strong>AdBlocker</strong> to view this page.</p>'),
	), $atts ) );

	$message 		= "{$message}";

	ob_start();
	echo "
<div class='bsaBlurWrapper' style='display:none'>
	<div class='bsaBlurInner'>
		<div class='bsaBlurInnerContent'>
			".$message."
		</div>
	</div>
</div>
<div class='afs_ads'>&nbsp;</div>
<script>
(function ($) {
    var message = '{$message}';
	// Define a function for showing the message.
	// Set a timeout of 2 seconds to give adblocker
	// a chance to do its thing
	var tryMessage = function() {
		setTimeout(function() {
			if(!document.getElementsByClassName) return;
			var ads = document.getElementsByClassName('afs_ads'),
				ad  = ads[ads.length - 1];
			if(!ad
				|| ad.innerHTML.length == 0
				|| ad.clientHeight === 0) {
				$('body').addClass('bsaBlurContent');
				$('.bsaBlurWrapper').appendTo('body').fadeIn();
				//window.location.href = '[URL of the donate page. Remove the two slashes at the start of thsi line to enable.]';
			} else {
				ad.style.display = 'none';
			}
		}, 2000);
	};
	/* Attach a listener for page load ... then show the message */
	if(window.addEventListener) {
		window.addEventListener('load', tryMessage, false);
	} else {
		window.attachEvent('onload', tryMessage); //IE
	}
})(jQuery);
</script>
	";
	return ob_get_clean();
}

add_action( 'admin_bar_menu', 'ads_pro_bar_link', 999 );
function ads_pro_bar_link( $wp_admin_bar ) {
	if ( 	get_option('bsa_pro_plugin_'.'link_bar') != 'yes' && is_multisite() && is_main_site() ||
		get_option('bsa_pro_plugin_'.'link_bar') != 'yes' && !is_multisite() ||
		get_option('bsa_pro_plugin_'.'link_bar') != 'yes' && get_current_blog_id() != 1 && is_main_site(1) ) {
		$model = new BSA_PRO_Model();
		$get_free_ads = $model->getUserCol(get_current_user_id(), 'free_ads');
		$free_ads = ((bsa_role() == 'admin') ? null : '('. get_option('bsa_pro_plugin_trans_free_ads') .' ' . (($get_free_ads['free_ads'] > 0) ? $get_free_ads['free_ads'] : 0) . ')');
		$link = ((bsa_role() == 'admin') ? 'admin.php?page=bsa-pro-sub-menu' : 'admin.php?page=bsa-pro-sub-menu-users');
		$args = array(
			'id'    => 'ads_pro_bar_link',
			'title' => '<img src="'.plugins_url('../frontend/img/bsa-icon.png', __FILE__).'" alt="" style="width:16px;display:inline-block;"> ADS PRO ' . $free_ads,
			'href'  => get_admin_url(1).$link,
			'meta'  => array( 'class' => 'ads_pro_bar_link' ),
			'icon'  => plugins_url('../frontend/img/bsa-icon.png', __FILE__)
		);
		if ( $get_free_ads['free_ads'] >= 0 or bsa_role() == 'admin') {
			$wp_admin_bar->add_node( $args );
		}
	}
}

add_action( 'bsa_cron_jobs','bsa_do_pending_tasks' );
function bsa_do_pending_tasks() { // CRON Function
	$cron = new BSA_PRO_Model();
	$cron->doCronTasks();
}

add_action( 'bsa_cron_views_stats','bsa_function_views_stats' );
function bsa_function_views_stats() {
	$model = new BSA_PRO_Model();
	$get_views_counter = get_option('bsa_pro_plugin_dashboard_views');
	$get_daily_counter = $model->getDailyViews(time() - (30 * 60));

	if ( $get_daily_counter[0] > 0 ) {
		update_option('bsa_pro_plugin_dashboard_views', ($get_views_counter + $get_daily_counter[0])); // increase views stats
	}

	wp_schedule_single_event( time() + (30 * 60), 'bsa_cron_views_stats', array( time() ) );
}