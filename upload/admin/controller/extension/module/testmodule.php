<?php
class ControllerExtensionModuleTestmodule extends Controller {
	private $error = array();

	public function index() {
      
		$this->load->language('extension/module/testmodule');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');

		$this->load->model('tool/image');

        $this->document->addStyle('view/javascript/jquery/jorderstatuscolor/colorpicker/css/bootstrap-colorpicker.css');
        $this->document->addScript('view/javascript/jquery/jorderstatuscolor/colorpicker/js/bootstrap-colorpicker.js');
        
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		    
		    
            $action = isset($this->request->post["action"]) ? $this->request->post["action"] : "";
		    
            
            
            
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('testmodule', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}
			
			$module_id = isset($this->request->get['module_id']) ? $this->request->get['module_id'] : $this->db->getLastId();

			$this->session->data['success'] = $this->language->get('text_success');
			
            switch($action){
    			case "save":	
    				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']. '&type=module', 'SSL'));
    			break;
    			case "save_edit":
    				$this->response->redirect($this->url->link('extension/module/testmodule', 'module_id='.$module_id.'&user_token=' . $this->session->data['user_token'], 'SSL'));
    			break;
    			case "save_new":
    				$this->response->redirect($this->url->link('extension/module/testmodule', 'user_token=' . $this->session->data['user_token'], 'SSL'));
    			break;
    		}
    		
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
			
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

        if (isset($this->error['item_name'])) {
			$data['error_item_name'] = $this->error['item_name'];
		} else {
			$data['error_item_name'] = '';
		}
        

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/testmodule', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/testmodule', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/testmodule', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/testmodule', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['class_suffix'])) {
			$data['class_suffix'] = $this->request->post['class_suffix'];
		} elseif (!empty($module_info) && isset($module_info['class_suffix'])) {
			$data['class_suffix'] = $module_info['class_suffix'];
		} else {
			$data['class_suffix'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		if (isset($this->request->post['item'])) {
			$data['testmodule'] = $this->request->post['item'];
		} elseif (!empty($module_info) && isset($module_info['item'])) {
			$data['testmodule'] = $module_info['item'];
		} else {
			$data['testmodule'] = '';
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if(!empty($data['testmodule'])) {
			foreach($data['testmodule'] as $item) {
				$testmodule[] = array(
					'image' => $item['image'],
					'image_url' => !empty($item['image']) ? $this->model_tool_image->resize($item['image'] , 120, 120) : $data['placeholder'],
					'name' =>  $item['item_name'],
					'text' => $item['text'],
					'class_prefix' => $item['class_prefix'],
					'color' => $item['color'],
					'link' => $item['link'],
					'sort' => $item['sort'],
				);
			}
			$data['testmodule'] = $testmodule;
		}

			if (isset($this->request->post['nb_column0'])) {
				$data['nb_column0'] = $this->request->post['nb_column0'];
			} elseif (!empty($module_info) && isset($module_info['nb_column0'])) {
				$data['nb_column0'] = $module_info['nb_column0'];
			} else {
				$data['nb_column0'] = 4;
			}
			
			if (isset($this->request->post['nb_column1'])) {
				$data['nb_column1'] = $this->request->post['nb_column1'];
			} elseif (!empty($module_info) && isset($module_info['nb_column1'])) {
				$data['nb_column1'] = $module_info['nb_column1'];
			} else {
				$data['nb_column1'] = 4;
			}
			
			if (isset($this->request->post['nb_column2'])) {
				$data['nb_column2'] = $this->request->post['nb_column2'];
			} elseif (!empty($module_info) && isset($module_info['nb_column2'])) {
				$data['nb_column2'] = $module_info['nb_column2'];
			} else {
				$data['nb_column2'] = 3;
			}
			
			if (isset($this->request->post['nb_column3'])) {
				$data['nb_column3'] = $this->request->post['nb_column3'];
			} elseif (!empty($module_info) && isset($module_info['nb_column3'])) {
				$data['nb_column3'] = $module_info['nb_column3'];
			} else {
				$data['nb_column3'] = 2;
			}
			
			if (isset($this->request->post['nb_column4'])) {
				$data['nb_column4'] = $this->request->post['nb_column4'];
			} elseif (!empty($module_info) && isset($module_info['nb_column4'])) {
				$data['nb_column4'] = $module_info['nb_column4'];
			} else {
				$data['nb_column4'] = 1;
			}
			
			if (isset($this->request->post['width'])) {
				$data['width'] = $this->request->post['width'];
			} elseif (!empty($module_info) && isset($module_info['width'])) {
				$data['width'] = $module_info['width'];
			} else {
				$data['width'] = 100;
			}
			
			if (isset($this->request->post['height'])) {
				$data['height'] = $this->request->post['height'];
			} elseif (!empty($module_info) && isset($module_info['height'])) {
				$data['height'] = $module_info['height'];
			} else {
				$data['height'] = 100;
			}
			//===========================* Carousel Effect *=======================
			
			if (isset($this->request->post['rtl'])) {
				$data['rtl'] = $this->request->post['rtl'];
			} elseif (!empty($module_info) && isset($module_info['rtl'])) {
				$data['rtl'] = $module_info['rtl'];
			} else {
				$data['rtl'] = 1;
			}
			if (isset($this->request->post['item_link_target'])) {
				$data['item_link_target'] = $this->request->post['item_link_target'];
			} elseif (!empty($module_info) && isset($module_info['item_link_target'])) {
				$data['item_link_target'] = $module_info['item_link_target'];
			} else {
				$data['item_link_target'] = "_self";
			}

			if (isset($this->request->post['center'])) {
				$data['center'] = $this->request->post['center'];
			} elseif (!empty($module_info) && isset($module_info['center'])) {
				$data['center'] = $module_info['center'];
			} else {
				$data['center'] = 0;
			}
			if (isset($this->request->post['loop'])) {
				$data['loop'] = $this->request->post['loop'];
			} elseif (!empty($module_info) && isset($module_info['loop'])) {
				$data['loop'] = $module_info['loop'];
			} else {
				$data['loop'] = 1;
			}
			if (isset($this->request->post['autoplay'])) {
				$data['autoplay'] = $this->request->post['autoplay'];
			} elseif (!empty($module_info) && isset($module_info['autoplay'])) {
				$data['autoplay'] = $module_info['autoplay'];
			} else {
				$data['autoplay'] = 1;
			}
			if (isset($this->request->post['mousedrag'])) {
				$data['mousedrag'] = $this->request->post['mousedrag'];
			} elseif (!empty($module_info)&& isset($module_info['mousedrag'])) {
				$data['mousedrag'] = $module_info['mousedrag'];
			} else {
				$data['mousedrag'] = 1;
			}
			if (isset($this->request->post['autoheight'])) {
				$data['autoheight'] = $this->request->post['autoheight'];
			} elseif (!empty($module_info)&& isset($module_info['autoheight'])) {
				$data['autoheight'] = $module_info['autoheight'];
			} else {
				$data['autoheight'] = 0;
			}
			if (isset($this->request->post['autowidth'])) {
				$data['autowidth'] = $this->request->post['autowidth'];
			} elseif (!empty($module_info)&& isset($module_info['autowidth'])) {
				$data['autowidth'] = $module_info['autowidth'];
			} else {
				$data['autowidth'] = 0;
			}
			
			if (isset($this->request->post['delay'])) {
				$data['delay'] = $this->request->post['delay'];
			} elseif (!empty($module_info) && isset($module_info['delay'])) {
				$data['delay'] = $module_info['delay'];
			} else {
				$data['delay'] = 4;
			}
			if (isset($this->request->post['arrows'])) {
				$data['arrows'] = $this->request->post['arrows'];
			} elseif (!empty($module_info) && isset($module_info['arrows'])) {
				$data['arrows'] = $module_info['arrows'];
			} else {
				$data['arrows'] = 1;
			}
			if (isset($this->request->post['pagination'])) {
				$data['pagination'] = $this->request->post['pagination'];
			} elseif (!empty($module_info) && isset($module_info['pagination'])) {
				$data['pagination'] = $module_info['pagination'];
			} else {
				$data['pagination'] = 0;
			}
			if (isset($this->request->post['lazyload'])) {
				$data['lazyload'] = $this->request->post['lazyload'];
			} elseif (!empty($module_info)&& isset($module_info['lazyload'])) {
				$data['lazyload'] = $module_info['lazyload'];
			} else {
				$data['lazyload'] = 1;
			}
			if (isset($this->request->post['hoverpause'])) {
				$data['hoverpause'] = $this->request->post['hoverpause'];
			} elseif (!empty($module_info)&& isset($module_info['hoverpause'])) {
				$data['hoverpause'] = $module_info['hoverpause'];
			} else {
				$data['hoverpause'] = 1;
			}
			
			if (isset($this->request->post['speed'])) {
				$data['speed'] = $this->request->post['speed'];
			} elseif (!empty($module_info)&& isset($module_info['speed'])) {
				$data['speed'] = $module_info['speed'];
			} else {
				$data['speed'] = 0.6;
			}
			if (isset($this->request->post['margin'])) {
				$data['margin'] = $this->request->post['margin'];
			} elseif (!empty($module_info)&& isset($module_info['margin'])) {
				$data['margin'] = $module_info['margin'];
			} else {
				$data['margin'] = 7;
			}

			if (isset($this->request->post['is_carousel'])) {
				$data['is_carousel'] = $this->request->post['is_carousel'];
			} elseif (!empty($module_info)&& isset($module_info['is_carousel'])) {
				$data['is_carousel'] = $module_info['is_carousel'];
			} else {
				$data['is_carousel'] = 0;
			}
			
			$data['store_layouts'] = array(
				'layout1' 	=> $this->language->get('value_layout1'),
				'layout2' 	=> $this->language->get('value_layout2')
			);

			if (isset($this->request->post['store_layout'])) {
				$data['store_layout'] = $this->request->post['store_layout'];
			} elseif (!empty($module_info)&& isset($module_info['store_layout'])) {
				$data['store_layout'] = $module_info['store_layout'];
			} else {
				$data['store_layout'] = 'default';
			}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/testmodule', $data));
	}
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/testmodule')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} else {
            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
                $this->error['name'] = $this->language->get('error_name');
            }

            foreach ($this->request->post['item'] as $key => $value) {
                if ((utf8_strlen($value['item_name']) < 3) || (utf8_strlen($value['item_name']) > 64)) {
                    $this->error['item_name'][$key] = $this->language->get('error_item_name');
                }
            }
        }
		return !$this->error;
	}
}