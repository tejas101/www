<?php
	
	require_once( EMFW__PLUGIN_DIR . 'Mailchimp.php' );

	class EMFWMCData {
		
		private $apikey;
		private $mc;
		private $error;
		private $invalidapi;
		
		public function __construct($apikey) {
			$this->invalidapi = false;
			if (strlen($apikey) < 5) $this->apikey = null;
			else $this->apikey = $apikey;
			$this->error = array();
			try {
    		$this->mc = new Mailchimp($this->apikey);
			} catch (Exception $e) {
					$this->error['status'] = "error";
					$this->error['code'] = -99;
					$this->error['name'] = "Unknown_Exception";
					$this->error['error'] = $e->getMessage();
					$this->invalidapi = true;
			}
		}
		
		public function __destruct() {
			$this->mc = null;
    }
		
		public function check_apikey() {
			$retval = false;
			if (!$this->invalidapi) {
				try {
	    		$res = $this->mc->helper->ping();
				} catch (Exception $e) {
						$this->error['status'] = "error";
						$this->error['code'] = -99;
						$this->error['name'] = "Unknown_Exception";
						$this->error['error'] = $e->getMessage();
				}
				if ($res['msg'] == "Everything's Chimpy!") $retval = true;
				else $this->error = $res;
			}
			return $retval;
		}
		
		public function get_error() {
			return $this->error;
		}
		
		public function get_alllists() {
			$retval = array();
			if (!$this->invalidapi) {
				try {
	    		$retval = $this->mc->lists->getList();
				} catch (Exception $e) {
						$this->error['status'] = "error";
						$this->error['code'] = -99;
						$this->error['name'] = "Unknown_Exception";
						$this->error['error'] = $e->getMessage();
				}
			}
			return $retval;
		}
		
		public function get_formfields($listid) {
			$retval = array();
			if (!$this->invalidapi) {
				try {
	    		$retval = $this->mc->lists->mergeVars(array($listid));
				} catch (Exception $e) {
						$this->error['status'] = "error";
						$this->error['code'] = -99;
						$this->error['name'] = "Unknown_Exception";
						$this->error['error'] = $e->getMessage();
				}
			}
			return $retval;
		}
		
		public function get_listdropdown($fid, $fname, $defvalue) {
			$retval = "";
			// Create list data
			$listdata = $this->get_alllists();
			$sorted = array();
			for ($i = 0; $i < $listdata['total']; $i++) {
				$sorted[$listdata['data'][$i]['id']] = $listdata['data'][$i]['name'];
			}
			asort($sorted);
			// Generate HTML
			$retval = '<select id="' . $fid . '" name="' . $fname . '">';
			$retval .= '<option value="-1">' . __( 'Please select a list', 'emfw_language_domain' ) . '</option>';
			foreach ($sorted as $key => $value) {
				$additional = "";
				if ($defvalue == $key) $additional = "selected";
				$retval .= '<option value="' . $key . '" ' . $additional . '>' . $value . '</option>';
			}
			$retval .= '</select>';
			return $retval;
		}
		
		// Generate one HTML field
		private function get_htmlformfield($ftype, $helptext, $fname, $fsize, $fchoices, $req) {
			$retval = "";
			switch($ftype) {
				default:
				case 'email':
				case 'text':
				case 'number':
				case 'zipcode':
				case 'phone':
				case 'website':
				case 'imageurl':
					$retval	.= '<input type="text" placeholder="E-Mail Address" name="' . $fname . '" class="' . $ftype . ($req == 1 ? ' emfw-require' : '') . '" />';
					break;
				case 'dropdown':
					$retval	.= '<select name="' . $fname . '" class="' . $ftype . ($req == 1 ? ' emfw-require' : '') . '" >';
						if (count($fchoices) > 0) : foreach($fchoices as $ok => $ov) :
								$o	.= '<option value="' . htmlentities($ov, ENT_QUOTES) . '">' . $ov . '</option>';
						endforeach; endif;
					$retval	.= '</select>';
					break;
				case 'address':
					$retval .= '<input type="text" name="' . $fname . '" class="' . $ftype . ($req == 1 ? ' emfw-require' : '') . '" placeholder="' . __("Street Address", "emfw_language_domain") . '" /><br />';
					$retval	.= '<input type="text" name="' . $fname . '-add2" class="' . $ftype . ($req == 1 ? ' emfw-require' : '') . '" placeholder="' . __("Apt/Suite", "emfw_language_domain") . '" /><br />';
					$retval	.= '<input type="text" name="' . $fname . '-city" class="' . $ftype . ($req == 1 ? ' emfw-require' : '') . '" placeholder="' . __("City", "emfw_language_domain") . '" /><br />';
					$retval	.= '<input type="text" name="' . $fname . '-state" class="' . $ftype . ($req == 1 ? ' emfw-require' : '') . '" placeholder="' . __("State", "emfw_language_domain") . '" /><br />';
					$retval	.= '<input type="text" name="' . $fname . '-zip" class="' . $ftype . ($req == 1 ? ' emfw-require' : '') . '" placeholder="' . __("Zip", "emfw_language_domain") . '" />';
					break;
				case 'radio':
					if (count($fchoices) > 0) : $ct = 0; foreach($fchoices as $ok => $ov) :
						$ct++;
						$retval	.= '<input type="radio" name="' . $fname . '" class="' . $ftype . ($req == 1 ? ' emfw-require' : '') . '" value="' . htmlentities($ov, ENT_QUOTES) . '" />' . $ov;
						if ($ct < count($fchoices))
							$retval	.= '<br />';
					endforeach; endif;
					break;
				case 'date':
				case 'birthday':
					$retval	.= '<input type="text" name="' . $fname . '" class="' . $ftype . ($req == 1 ? ' emfw-require' : '') . '" />';
					break;
			}
			return $retval;
		}
		
		// Generate ajax javascript for form post
		private function get_ajaxpostscript($listid, $redirurl, $hideform, $containerid) {
			$retval = '';
			$retval .= '<script type="text/javascript">';
			$retval .= '	$emfw = jQuery.noConflict();';
			$retval .= '	jQuery(document).ready(function($emfw) {';
			$retval .= '		$emfw("#frm_' . $listid . '").submit(function( event ) {';
			$retval .= '			$emfw("html,body").css("cursor", "progress");';
			$retval .= '			event.preventDefault();';
			$retval .= '			$emfw.ajax({';
			$retval .= '				type:	"POST",';
			$retval .= '				url:	"' . EMFW_URL_WP_AJAX . '",';
			$retval .= '				data:	{';
			$retval .= '					form_data: $emfw(this).serialize(), ';
			$retval .= '					action: "emfw_ajaxActions", ';
			$retval .= '				},';
			$retval .= '				dataType: "text",';
			$retval .= '				success: function(msg) {';
			$retval .= '					$emfw("html,body").css("cursor", "default");';
			if ($hideform)  {
				$retval .= '				$emfw("#' . $containerid . '").hide();';
			}
			if (strlen($redirurl) > 9 && strpos($redirurl, 'http://') !== false)  {
				$retval .= '				window.location.replace("' . $redirurl . '");';
			} else {
				$retval .= '					alert(msg);';
			}
			$retval .= '				}';
			$retval .= '			});';
			$retval .= '			return false;';
			$retval .= '		});';
			$retval .= '	});';
			$retval .= '</script>';
			return $retval;
		}
		
		// Generate the full HTML Form
		public function get_form_display($listid, $containerid) {
			$retval = "";
			$fields = $this->get_formfields($listid);
			if ($fields['success_count'] > 0) {
				$options = get_option('emfw_options', emfw_get_defaultvalues());
				$retval .= $this->get_ajaxpostscript($listid, $options['lists'][$listid]['redirurl'], $options['lists'][$listid]['hideform'], $containerid);
				$retval .= '<form id="frm_' . $listid . '" method="post">';
				$retval .= '<div class="emfw_allformfields">';
				$values = $fields['data'][0]['merge_vars'];
				for ($i = 0; $i < 1; $i++) {
					if ($values[$i]['public'] == 1) {
						$retval .= '<div class="emfw_fieldgroup">';
						$reqfiled = '';
						if ($values[$i]['req'] == 1) $reqfiled = '&nbsp;<span class="emfw_required">*</span>';
						/*$retval .= '<div class="emfw_fieldname">' . $values[$i]['name'] . $reqfiled . '</div>'; */ 
						$retval .= '<div class="emfw_fieldvalue" >' . $this->get_htmlformfield($values[$i]['field_type'], $values[$i]['helptext'], $values[$i]['tag'], $values[$i]['size'], $values[$i]['choices'], $values[$i]['req']) . '</div>';
						$retval .= '</div>';
					}
				}
				$retval .= '<div class="emfw_submitbtncontainer"><input type="submit" class="emfw_submitbtn" name="subscribe" value="' . __("Subscribe", "emfw_language_domain") . '"></div>';
				$retval .= '<input type="hidden" value="' . $listid . '" name="listid" />';
				$retval .= '<input type="hidden" value="' . $options['lists'][$listid]['redirurl'] . '" name="redirurl" />';
				$retval .= '<input type="hidden" value="' . $options['lists'][$listid]['hideform'] . '" name="hideform" />';
				$retval .= '</div></form>';
			} else {
				$retval = __( 'Failed to display MailChimp Form', 'emfw_language_domain' );
			}
			return $retval;
		}
		
		// Check wether required fields are filled out
		private function check_reqs($p) {
			$retval = array('labels' => '', 'msg' => '');
			$fields = $this->get_formfields($p['listid']);
			if ($fields['success_count'] > 0) {
				$values = $fields['data'][0]['merge_vars'];
				for ($i = 0; $i < count($values); $i++) {
					if ($values[$i]['req'] == 1 && $values[$i]['public'] == 1) {
						if (strlen($p[$values[$i]['tag']]) == 0) {
							if (strlen($retval['labels']) == 0) $retval['labels'] .= $values[$i]['name'];
							else $retval['labels'] .= ', ' . $values[$i]['name'];
						}
					}
				}
				if (strlen($retval['labels']) > 0) $retval['msg'] = __( 'The following fields are required: ', 'emfw_language_domain' );
			}
			return $retval;
		}
		
		// Ajax subscription to MailChimp
		public function ajax_subscribe($p) {
			$retval = array('msg' => '', 'code' => '');
			// Check required fields
			$res = $this->check_reqs($p);
			if (strlen($res['msg']) > 0) {
				$retval['msg'] = $res['msg'] . $res['labels'];
			} else {
				$mv = array();
				$email = "";
				$lid = $p['listid'];
				// Process fields
				$fields = $this->get_formfields($lid);
				if ($fields['success_count'] > 0) {
					$values = $fields['data'][0]['merge_vars'];
					for ($i = 0; $i < count($values); $i++) {
						// Only public fields
						if ($values[$i]['public'] == 1) {
							$this->get_htmlformfield($values[$i]['field_type'], $values[$i]['default'], $values[$i]['helptext'], $values[$i]['tag'], $values[$i]['size'], $values[$i]['choices'], $values[$i]['req']) . '</div>';
							switch($values[$i]['field_type']) {
								case 'email': $email = $p[$values[$i]['tag']]; break;
								case 'address':
									$mv[$values[$i]['tag']]	= array(
										'addr1'		=> $p[$values[$i]['tag']],
										'addr2'		=> $p[$values[$i]['tag'] . '-add2'],
										'city'		=> $p[$values[$i]['tag'] . '-city'],
										'state'		=> $p[$values[$i]['tag'] . '-state'],
										'zip'			=> $p[$values[$i]['tag'] . '-zip'],
										'country'	=> 'US'
									);
								break;
								default: $mv[$values[$i]['tag']]	= $p[$values[$i]['tag']]; break;
							}
						}
					}
				}
				// Try to subscribe
				try {
					$options = get_option('emfw_options', emfw_get_defaultvalues());
					$double_optin = true;
					$update_existing = false;
					$replace_interests = true;
					$send_welcome = false;
					if (isset($options['lists'][$lid])) {
						$double_optin = $options['lists'][$lid]['doubleoptin'];
						$update_existing = $options['lists'][$lid]['updateexisting'];
						$replace_interests = $options['lists'][$lid]['replaceig'];
						$send_welcome = $options['lists'][$lid]['welcomeemail'];
					}
	    		$res = $this->mc->lists->subscribe($lid, array('email' => $email), $mv, "html", $double_optin, $update_existing, $replace_interests, $send_welcome);
				} catch (Exception $e) {
					$retval['msg'] = $this->get_errorMsg($e->getCode());
				}
				// Set returning values
				if (strlen($retval['msg']) == 0) {
					if ($double_optin) $retval['msg'] = __("Thank You for subscribing! Check your email for the confirmation message.", 'emfw_language_domain' );
					else $retval['msg'] = __("Thank You for subscribing!", 'emfw_language_domain' );
					$retval['code'] = 1;
				} else {
					$retval['code'] = 0;
				}
			}
			return $retval;
		}
		
		private function get_errorMsg($error) {
			//Server Errors	
			$errorcode['-32601'][1] = 'ServerError_MethodUnknown';
			$errorcode['-32602'][1] = 'ServerError_InvalidParameters';
			$errorcode['-99'][1] = 'Unknown_Exception';
			$errorcode['-98'][1] = 'Request_TimedOut';
			$errorcode['-92'][1] = 'Zend_Uri_Exception';
			$errorcode['-91'][1] = 'PDOException';
			$errorcode['-91'][1] = 'Avesta_Db_Exception';
			$errorcode['-90'][1] = 'XML_RPC2_Exception';
			$errorcode['-90'][1] = 'XML_RPC2_FaultException';
			$errorcode['-50'][1] = 'Too_Many_Connections';
			$errorcode['0'][1] = 'Parse_Exception';
			
			$errormessage[1] = __("Sorry, we can't connect to MailChimp at this time. Please come back later and try again.", 'emfw_language_domain' );

			//API User or API Key error
			$errorcode['100'][2] = 'User_Unknown';
			$errorcode['101'][2] = 'User_Disabled';
			$errorcode['102'][2] = 'User_DoesNotExist';
			$errorcode['103'][2] = 'User_NotApproved';
			$errorcode['104'][2] = 'Invalid_ApiKey';
			$errorcode['105'][2] = 'User_UnderMaintenance';
			$errorcode['106'][2] = 'Invalid_AppKey';
			$errorcode['107'][2] = 'Invalid_IP';
			$errorcode['108'][2] = 'User_DoesExist';
			$errorcode['109'][2] = 'User_InvalidRole';
			$errorcode['120'][2] = 'User_InvalidAction';
			$errorcode['121'][2] = 'User_MissingEmail';
			$errorcode['122'][2] = 'User_CannotSendCampaign';
			$errorcode['123'][2] = 'User_MissingModuleOutbox';
			$errorcode['124'][2] = 'User_ModuleAlreadyPurchased';
			$errorcode['125'][2] = 'User_ModuleNotPurchased';
			$errorcode['126'][2] = 'User_NotEnoughCredit';
			$errorcode['127'][2] = 'MC_InvalidPayment';
			
			$errormessage[2] = __("Sorry, this MailChimp account does not exist.", 'emfw_language_domain' );
			
			// List errors 
			$errorcode['200'][3] = 'List_DoesNotExist';
			$errorcode['210'][3] = 'List_InvalidInterestFieldType';
			$errorcode['211'][3] = 'List_InvalidOption';
			$errorcode['212'][3] = 'List_InvalidUnsubMember';
			$errorcode['213'][3] = 'List_InvalidBounceMember';
			
			$errormessage[3] = __("Sorry,  this list does not exist.", 'emfw_language_domain' );
			
			//Already subscribed or unsubscribed
			$errorcode['214'][4] = 'List_AlreadySubscribed';
			$errorcode['215'][4] = 'List_NotSubscribed';
			$errorcode['220'][4] = 'List_InvalidImport';
			$errorcode['221'][4] = 'MC_PastedList_Duplicate';
			$errorcode['222'][4] = 'MC_PastedList_InvalidImport';
			$errorcode['230'][4] = 'Email_AlreadySubscribed';
			$errorcode['231'][4] = 'Email_AlreadyUnsubscribed';
			$errorcode['232'][4] = 'Email_NotExists';
			$errorcode['233'][4] = 'Email_NotSubscribed';
			
			$errormessage[4] = __("Sorry, you are already subscribed to this list.", 'emfw_language_domain' );
			
			// General Message 
			$errorcode['250'][5] = 'List_MergeFieldRequired';
			$errorcode['251'][5] = 'List_CannotRemoveEmailMerge';
			$errorcode['252'][5] = 'List_Merge_InvalidMergeID';
			$errorcode['253'][5] = 'List_TooManyMergeFields';
			$errorcode['254'][5] = 'List_InvalidMergeField';
			$errorcode['270'][5] = 'List_InvalidInterestGroup';
			$errorcode['271'][5] = 'List_TooManyInterestGroups';
			$errorcode['300'][5] = 'Campaign_DoesNotExist';
			$errorcode['301'][5] = 'Campaign_StatsNotAvailable';
			$errorcode['310'][5] = 'Campaign_InvalidAbsplit';
			$errorcode['311'][5] = 'Campaign_InvalidContent';
			$errorcode['312'][5] = 'Campaign_InvalidOption';
			$errorcode['313'][5] = 'Campaign_InvalidStatus';
			$errorcode['314'][5] = 'Campaign_NotSaved';
			$errorcode['315'][5] = 'Campaign_InvalidSegment';
			$errorcode['316'][5] = 'Campaign_InvalidRss';
			$errorcode['317'][5] = 'Campaign_InvalidAuto';
			$errorcode['318'][5] = 'MC_ContentImport_InvalidArchive';
			$errorcode['319'][5] = 'Campaign_BounceMissing';
			$errorcode['330'][5] = 'Invalid_EcommOrder';
			$errorcode['350'][5] = 'Absplit_UnknownError';
			$errorcode['351'][5] = 'Absplit_UnknownSplitTest';
			$errorcode['352'][5] = 'Absplit_UnknownTestType';
			$errorcode['353'][5] = 'Absplit_UnknownWaitUnit';
			$errorcode['354'][5] = 'Absplit_UnknownWinnerType';
			$errorcode['355'][5] = 'Absplit_WinnerNotSelected';
			
			$errormessage[5] = __('Sorry, MailChimp could not process your signup.', 'emfw_language_domain' );
			
			// Validation errors
			$errorcode['500'][6] = 'Invalid_Analytics';
			$errorcode['503'][6] = 'Invalid_SendType';
			$errorcode['504'][6] = 'Invalid_Template';
			$errorcode['505'][6] = 'Invalid_TrackingOptions';
			$errorcode['506'][6] = 'Invalid_Options';
			$errorcode['507'][6] = 'Invalid_Folder';
			$errorcode['550'][6] = 'Module_Unknown';
			$errorcode['551'][6] = 'MonthlyPlan_Unknown';
			$errorcode['552'][6] = 'Order_TypeUnknown';
			$errorcode['553'][6] = 'Invalid_PagingLimit';
			$errorcode['554'][6] = 'Invalid_PagingStart';
			$errorcode['555'][6] = 'Max_Size_Reached';
			$errorcode['556'][6] = 'MC_SearchException';
			
			$errormessage[6] = __("Sorry, MailChimp doesn't like the data you are trying to send.", 'emfw_language_domain' );

			// Validate date and time field
			$errorcode['501'][7] = 'Invalid_DateTimel';
			
			$errormessage[7] = __("Sorry, that date and time is invalid. Please try again.", 'emfw_language_domain' );
			
			//Validate Email
			$errorcode['502'][8] = 'Invalid_Email';
			
			$errormessage[8] = __("Sorry, that email address is invalid. Please try again.", 'emfw_language_domain' );
			
			// Validate URL fields
			$errorcode['508'][9] = 'Invalid_URL';
			
			$errormessage[9] = __("Sorry, that URL is invalid. Please try again.", 'emfw_language_domain' );

			// Get error message
			
			foreach ($errorcode as $eid => $value) {
				if ($eid == $error) {
					foreach ($value as  $key => $mssg) {
						$Message = $errormessage[$key];
					}			
				}
			}	
			return $Message;
		} 
	}
?>