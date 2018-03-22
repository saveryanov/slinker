<?php
class ControllerModuleSlinker extends Controller {
	private $error = array();
	
	// Admin pages
	public function index() {
		$this->process_page('');
	}
	public function textlinker() {
		$this->process_page('textlinker');
	}
	public function sitemap() {
		$this->process_page('sitemap');
	}
	public function keyphrases() {
		$this->process_page('keyphrases');
	}
	
	
	// process admin page
	public function process_page($slinkerpage) {
		header('X-XSS-Protection: 1');
		require_once("../slinker/includes/boot.php");
		require_once("../slinker/includes/config.php");
		require_once("../slinker/includes/templates.php");
		require_once("../slinker/includes/database.php");
		require_once("../slinker/includes/tools.php");
		require_once("../slinker/includes/stemmer.php");
		require_once("../slinker/includes/search.php");
		require_once("../slinker/includes/callbacks.php");
		require_once('../slinker/includes/libs/Smarty.class.php');
		$data = array();
		
		$data['debug_content'] = '';
		$this->load->language('module/slinker');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		$slinker_pages = array('textlinker', 'sitemap', 'keyphrases', 'newkeyphrase');
		$data['slinkerpage'] = $slinkerpage;
		if(!in_array($data['slinkerpage'], $slinker_pages)) {
			$data['slinkerpage'] = '';
		}

		switch($data['slinkerpage']) {
			case '':
				$this->sitelinker_page($data);
				break;
			case 'textlinker':
				$this->textlinker_page($data);
				break;
			case 'sitemap':
				$this->sitemap_page($data);
				break;
			case 'keyphrases':
				$this->saved_keywords_page($data);
				//$data['slinker_tpl'] = saved_keywords_callback();
				break;
		}
		
		
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/slinker', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/slinker/' . $slinkerpage, 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['token'] = $this->session->data['token'];
		
		$this->response->setOutput($this->load->view('module/slinker.tpl', $data));
	}
	
	
	
	
	// Ajax callbacks
	public function delete_keyphrase_callback() {
		header('X-XSS-Protection: 1');
		require_once("../slinker/includes/boot.php");
		require_once("../slinker/includes/config.php");
		require_once("../slinker/includes/templates.php");
		require_once("../slinker/includes/database.php");
		require_once("../slinker/includes/tools.php");
		require_once("../slinker/includes/stemmer.php");
		require_once("../slinker/includes/search.php");
		require_once("../slinker/includes/callbacks.php");
		require_once('../slinker/includes/libs/Smarty.class.php');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!empty($this->request->post['id'])) {
				
				delete_keyphrase($this->request->post['id']);
				
				$this->response->setOutput(
					json_encode(
						array('success')
					)
				);
				return;
			}
		}
	}
	
	
	public function batch_sitelinker_callback() {
		header('X-XSS-Protection: 1');
		require_once("../slinker/includes/boot.php");
		require_once("../slinker/includes/config.php");
		require_once("../slinker/includes/templates.php");
		require_once("../slinker/includes/database.php");
		require_once("../slinker/includes/tools.php");
		require_once("../slinker/includes/stemmer.php");
		require_once("../slinker/includes/search.php");
		require_once("../slinker/includes/callbacks.php");
		require_once('../slinker/includes/libs/Smarty.class.php');
		
		$data = array();
		$data['debug_content'] = '';
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$batch_size = $this->request->post['batch'];
			$this->sitelinker_page($data, $batch_size, $this->request->post['completed']);
			$batch_info = array(
				'htmlmodule_paths_linked' => isset($data['htmlmodule_paths_linked'])?$data['htmlmodule_paths_linked']:array(),
				'manufacturers_paths_linked' => isset($data['manufacturers_paths_linked'])?$data['manufacturers_paths_linked']:array(),
				'category_paths_linked' => isset($data['category_paths_linked'])?$data['category_paths_linked']:array(),
				'products_paths_linked' => isset($data['products_paths_linked'])?$data['products_paths_linked']:array(),
				'information_paths_linked' => isset($data['information_paths_linked'])?$data['information_paths_linked']:array(),
				'whatlinked' => isset($data['whatlinked'])?$data['whatlinked']:array(),
				'iscomplete' => $data['iscomplete'],
				'completed' => $this->request->post['completed'] + $batch_size,
				//'post' => $this->request->post,
			);
			
			$this->response->setOutput(
				json_encode(
					$batch_info
				)
			);
				
		}
	}
	
	
	
	// Admin pages content callbacks
	public function saved_keywords_page(&$data) {
		
		$data['islinked'] = false;
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$data['islinked'] = true;
			$path = $this->request->post['slinker_path']; // Хост сайта
			$keyphrase = $this->request->post['slinker_keyphrase']; // Хост сайта
			$title = $this->request->post['slinker_title']; // Хост сайта
			
			$phrase_id = add_keyphrase ($keyphrase, $path, get_url_depth($path), $title);
			if(!empty($phrase_id)) {
				$data['success'] = true;
			} else {
				$data['success'] = false;
			}
		}
		$data['keyphrases'] = get_keyphrases('id ASC');
		$data['server_domain'] = $_SERVER['SERVER_NAME'];
	}

	
	
	public function sitemap_page(&$data) {
		
		$data['islinked'] = false;
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$data['islinked'] = true;
			$host = $this->request->post['slinker_domain']; // Хост сайта
			$data['sitemap_counters'] = process_sitemap_keywords($host);
		}
		$data['server_domain'] = $_SERVER['SERVER_NAME'];
	}
	
	public function textlinker_page(&$data) {
		
		$data['islinked'] = false;
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$data['islinked'] = true;
			$data['deletelinks'] = intval($this->request->post['slinker_deletelinks']);
			$data['skiplen'] = intval($this->request->post['slinker_skiplen']);
			$data['original_text'] = ($this->request->post['slinker_text']);
			
			$keyphrases = get_keyphrases();
			// линкуем текст!
			$data['linked_text'] = htmlspecialchars_decode($data['original_text']);
			$words = search_keyphrases_in_html($data['linked_text'], $keyphrases, $data['deletelinks']);
			$data['linked_text'] = (search_create_text_from_words($words, $data['skiplen'], $keyphrases));
		}
	}
	
	public function sitelinker_page(&$data, $batch_size = -1, $completed = 0) {
		$batch_remaining = $batch_size;
		$shift_remaining = $completed;
		$data['islinked'] = false;
		$data['whatlinked'] = array();
		$data['iscomplete'] = false;
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('localisation/language');
			$languages = $this->model_localisation_language->getLanguages();
			$language_codes = array();
			foreach($languages as $code=>$language) {
				$language_codes[$language['language_id']] = $language['code'];
			}

			//$this->model_setting_setting->editSetting('slinker', $this->request->post);
			//$data['debug_content'] .= dpms($this->request->post);
			
			$data['islinked'] = true;
			$data['deletelinks'] = intval($this->request->post['slinker_deletelinks']);
			$data['skiplen'] = intval($this->request->post['slinker_skiplen']);
			$data['slinker_link_categories'] = intval($this->request->post['slinker_link_categories']);
			$data['slinker_link_manufacturers'] = intval($this->request->post['slinker_link_manufacturers']);
			$data['slinker_link_products'] = intval($this->request->post['slinker_link_products']);
			$data['slinker_link_information'] = intval($this->request->post['slinker_link_information']);
			$data['slinker_link_htmlmodule'] = intval($this->request->post['slinker_link_htmlmodule']);
			$keyphrases = get_keyphrases();
			
			$config_url = HTTP_CATALOG;
			$config_secure = HTTPS_CATALOG;
			$url = new Url($config_url, $config_secure ? $config_secure : $config_url);
			
			
			
			
			
			if($data['slinker_link_htmlmodule']) { // ЛИНКОВКА ВСЕХ HTML МОДУЛЕЙ
				$data['htmlmodule_paths_linked'] = array();
				
				$this->load->model('extension/module');
				$htmls = $this->model_extension_module->getModulesByCode('html');
				foreach ($htmls as $html) {
					
					$data['debug_content'] .= '<pre>';
					
					$data['debug_content'] .= print_r($html['module_id'] ,true) . '<br>';
					$data['debug_content'] .= print_r($html, true) . '<br>';
					
					$html_settings = unserialize($html['setting']);
					// линкуем текст!
					foreach($html_settings['module_description'] as $language_id=>$html_description) {
						
						if($shift_remaining > 0) {
							$shift_remaining--;
							continue;
						}
						if($batch_size > 0) {
							$batch_remaining--;
							if($batch_remaining < 0) {
								return;
							}
						}
						$data['whatlinked'][] = 'HTML модуль "' . $html['name'] . '" (' . $language_codes[$language_id] . ')';
						
						
						$html_settings['module_description'][$language_id]['description'] = htmlspecialchars_decode($html_settings['module_description'][$language_id]['description']);
						$words = search_keyphrases_in_html($html_settings['module_description'][$language_id]['description'], $keyphrases, $data['deletelinks']);
						$html_settings['module_description'][$language_id]['description'] = htmlspecialchars(search_create_text_from_words($words, $data['skiplen'], $keyphrases, $link_headers['Location']));
						
						$this->model_extension_module->editModule($html['module_id'], $html_settings);
						
						$data['htmlmodule_paths_linked'][] = array(
							'module_id' => $html['module_id'],
							'module_name' => $html['name'],
							'language_id' => $language_id,
							'language_code' => $language_codes[$language_id],
						);
					}
					
					$data['debug_content'] .= print_r($html_settings, true) . '<br>';
					$data['debug_content'] .= '</pre>';
				}
			}
			
			
			
			
			
			
			if($data['slinker_link_categories']) { // ЛИНКОВКА ВСЕХ КАТЕГОРИЙ
				$data['category_paths_linked'] = array();
				
				$this->load->model('catalog/category');
				$categories = $this->model_catalog_category->getCategories(0);
				foreach ($categories as $category) {
					$link = $url->link('product/category', 'path=' . $category['category_id']);
					$link = $this->rewrite($link);
					
					$link_headers = get_headers($link, 1);
					$category_descriptions = $this->model_catalog_category->getCategoryDescriptions($category['category_id']);
					$data['debug_content'] .= '<pre>' . print_r($category ,true) . print_r($category_descriptions ,true) . '<br>' . print_r($link_headers['Location'], true) . '<br>';
					
					foreach($category_descriptions as $language_id => $value) {
						if($shift_remaining > 0) {
							$shift_remaining--;
							continue;
						}
						if($batch_size > 0) {
							$batch_remaining--;
							if($batch_remaining < 0) {
								return;
							}
						}
						$data['whatlinked'][] = 'Категория "<a href="' . $link_headers['Location'] . '" target="_blank">' . $value['name'] . '</a>" (' . $language_codes[$language_id] . ')';
						
						$keyphrases_tmp = array();
						foreach($keyphrases as $phrase_id=>$keyphrase) {
							if($keyphrase['path'] != $link_headers['Location']) {
								$keyphrases_tmp[$phrase_id] = $keyphrase;
							}
						}
						// линкуем текст!
						$category_descriptions[$language_id]['description'] = htmlspecialchars_decode($category_descriptions[$language_id]['description']);
						$words = search_keyphrases_in_html($category_descriptions[$language_id]['description'], $keyphrases_tmp, $data['deletelinks']);
						$category_descriptions[$language_id]['description'] = htmlspecialchars(search_create_text_from_words($words, $data['skiplen'], $keyphrases_tmp, $link_headers['Location']));
						
						
						$sql_query = "UPDATE " . DB_PREFIX . "category_description SET description = '" . ($category_descriptions[$language_id]['description']) . "' WHERE category_id = '" . (int)$category['category_id'] . "' AND language_id = '" . (int)$language_id . "'";
						$this->db->query($sql_query);
						$data['debug_content'] .= $sql_query;
						
						$data['category_paths_linked'][] = array(
							'path' => $link_headers['Location'], 
							'category_id' => $category['category_id'],
							'category_name' => $value['name'],
							'language_id' => $language_id,
							'language_code' => $language_codes[$language_id],
						);
						
					}
					$data['debug_content'] .= print_r($category_descriptions ,true);
					
					$data['debug_content'] .= '</pre>';
				}
			}
			
			if($data['slinker_link_products']) { // ЛИНКОВКА ВСЕХ ТОВАРОВ
				$data['products_paths_linked'] = array();
				
				$this->load->model('catalog/product');
				$products = $this->model_catalog_product->getProducts();
				foreach ($products as $product) {
					$link = $url->link('product/product', 'path=' . $product['product_id']);
					$link = $this->rewrite($link);
					
					$link_headers = get_headers($link, 1); 
					$product_descriptions = $this->model_catalog_product->getProductDescriptions($product['product_id']);
					$data['debug_content'] .= '<pre>' . print_r($product ,true) . print_r($product_descriptions ,true) . '<br>' . print_r($link_headers['Location'], true) . '<br>';
					
					foreach($product_descriptions as $language_id => $value) {
						if($shift_remaining > 0) {
							$shift_remaining--;
							continue;
						}
						if($batch_size > 0) {
							$batch_remaining--;
							if($batch_remaining < 0) {
								return;
							}
						}
						$data['whatlinked'][] = 'Товар "<a href="' . $link_headers['Location'] . '" target="_blank">' . $value['name'] . '</a>" (' . $language_codes[$language_id] . ')';
						
						$keyphrases_tmp = array();
						foreach($keyphrases as $phrase_id=>$keyphrase) {
							if($keyphrase['path'] != $link_headers['Location']) {
								$keyphrases_tmp[$phrase_id] = $keyphrase;
							}
						}
						// линкуем текст!
						$product_descriptions[$language_id]['description'] = htmlspecialchars_decode($product_descriptions[$language_id]['description']);
						$words = search_keyphrases_in_html($product_descriptions[$language_id]['description'], $keyphrases_tmp, $data['deletelinks']);
						$product_descriptions[$language_id]['description'] = htmlspecialchars(search_create_text_from_words($words, $data['skiplen'], $keyphrases_tmp, $link_headers['Location']));
						
						
						$sql_query = "UPDATE " . DB_PREFIX . "product_description SET description = '" . ($product_descriptions[$language_id]['description']) . "' WHERE product_id = '" . (int)$product['product_id'] . "' AND language_id = '" . (int)$language_id . "'";
						$this->db->query($sql_query);
						$data['debug_content'] .= $sql_query;
						
						$data['products_paths_linked'][] = array(
							'path' => $link_headers['Location'], 
							'product_id' => $product['product_id'],
							'product_name' => $value['name'],
							'language_id' => $language_id,
							'language_code' => $language_codes[$language_id],
						);
						
					}
					$data['debug_content'] .= print_r($product_descriptions ,true);
					
					$data['debug_content'] .= '</pre>';
				}
			}
			
			
			if($data['slinker_link_manufacturers']) { // ЛИНКОВКА ВСЕХ ТОВАРОВ
				$data['manufacturers_paths_linked'] = array();
				
				$this->load->model('catalog/manufacturer');
				$manufacturers = $this->model_catalog_manufacturer->getManufacturers();
				foreach ($manufacturers as $manufacturer) {
					
					$link = $url->link('product/manufacturer', 'path=' . $manufacturer['manufacturer_id']);
					$link = $this->rewrite($link);
					
					$link_headers = get_headers($link, 1); 
					$manufacturer_descriptions = $this->model_catalog_manufacturer->getManufacturerDescriptions($manufacturer['manufacturer_id']);
					$data['debug_content'] .= '<pre>' . print_r($manufacturer ,true) . print_r($manufacturer_descriptions ,true) . '<br>' . print_r($link_headers['Location'], true) . '<br>';
					
					foreach($manufacturer_descriptions as $language_id => $value) {
						if($shift_remaining > 0) {
							$shift_remaining--;
							continue;
						}
						if($batch_size > 0) {
							$batch_remaining--;
							if($batch_remaining < 0) {
								return;
							}
						}
						$data['whatlinked'][] = 'Производитель "<a href="' . $link_headers['Location'] . '" target="_blank">' . $value['name'] . '</a>" (' . $language_codes[$language_id] . ')';
						
						$keyphrases_tmp = array();
						foreach($keyphrases as $phrase_id=>$keyphrase) {
							if($keyphrase['path'] != $link_headers['Location']) {
								$keyphrases_tmp[$phrase_id] = $keyphrase;
							}
						}
						// линкуем текст!
						$manufacturer_descriptions[$language_id]['description'] = htmlspecialchars_decode($manufacturer_descriptions[$language_id]['description']);
						$words = search_keyphrases_in_html($manufacturer_descriptions[$language_id]['description'], $keyphrases_tmp, $data['deletelinks']);
						$manufacturer_descriptions[$language_id]['description'] = htmlspecialchars(search_create_text_from_words($words, $data['skiplen'], $keyphrases_tmp, $link_headers['Location']));
						
						
						$sql_query = "UPDATE " . DB_PREFIX . "manufacturer_description SET description = '" . ($manufacturer_descriptions[$language_id]['description']) . "' WHERE manufacturer_id = '" . (int)$product['product_id'] . "' AND language_id = '" . (int)$language_id . "'";
						$this->db->query($sql_query);
						$data['debug_content'] .= $sql_query;
						
						$data['manufacturers_paths_linked'][] = array(
							'path' => $link_headers['Location'], 
							'manufacturer_id' => $manufacturer['manufacturer_id'],
							'manufacturer_name' => $value['name'],
							'language_id' => $language_id,
							'language_code' => $language_codes[$language_id],
						);
						
					}
					$data['debug_content'] .= print_r($manufacturer_descriptions ,true);
					
					$data['debug_content'] .= '</pre>';
				}
			}
			
			
			
			
			
		
		
		
			if($data['slinker_link_information']) { // ЛИНКОВКА ВСЕХ ТОВАРОВ
				$data['information_paths_linked'] = array();
				
				$this->load->model('catalog/information');
				$informations = $this->model_catalog_information->getInformations();
				foreach ($informations as $information) {
					
					$link = $this->url->link('catalog/information', 'information_id=' .  $information['information_id']);
					//$link = $this->rewrite($link);
					
					$link_headers = get_headers($link, 1); 
					$information_descriptions = $this->model_catalog_information->getInformationDescriptions($information['information_id']);
					$data['debug_content'] .= '<pre>' . print_r($information ,true) . print_r($information_descriptions ,true) . '<br>' . print_r($link_headers['Location'], true) . '<br>';
					
					foreach($information_descriptions as $language_id => $value) {
						if($shift_remaining > 0) {
							$shift_remaining--;
							continue;
						}
						if($batch_size > 0) {
							$batch_remaining--;
							if($batch_remaining < 0) {
								return;
							}
						}
						$data['whatlinked'][] = 'Информация "<a href="' . $link_headers['Location'] . '" target="_blank">' . $value['title'] . '</a>" (' . $language_codes[$language_id] . ')';
						
						$keyphrases_tmp = array();
						foreach($keyphrases as $phrase_id=>$keyphrase) {
							if($keyphrase['path'] != $link_headers['Location']) {
								$keyphrases_tmp[$phrase_id] = $keyphrase;
							}
						}
						// линкуем текст!
						$information_descriptions[$language_id]['description'] = htmlspecialchars_decode($information_descriptions[$language_id]['description']);
						$words = search_keyphrases_in_html($information_descriptions[$language_id]['description'], $keyphrases_tmp, $data['deletelinks']);
						$information_descriptions[$language_id]['description'] = htmlspecialchars(search_create_text_from_words($words, $data['skiplen'], $keyphrases_tmp, $link_headers['Location']));
						
						
						$sql_query = "UPDATE " . DB_PREFIX . "information_description SET description = '" . ($information_descriptions[$language_id]['description']) . "' WHERE information_id = '" . (int)$product['product_id'] . "' AND language_id = '" . (int)$language_id . "'";
						$this->db->query($sql_query);
						$data['debug_content'] .= $sql_query;
						
						$data['information_paths_linked'][] = array(
							'path' => $link_headers['Location'], 
							'link_raw' => $url->link('information/information', 'information_id=' .  $information['information_id']), 
							'link_rewrited' => $link, 
							'information_id' => $information['information_id'],
							'title' => $value['title'],
							'language_id' => $language_id,
							'language_code' => $language_codes[$language_id],
						);
						
					}
					$data['debug_content'] .= print_r($information_descriptions ,true);
					$data['debug_content'] .= print_r(
						array('link_raw' => $url->link('information/information', 'information_id=' .  $information['information_id']), 
							  'link_rewrited' => $link, )
					,true);
					
					$data['debug_content'] .= '</pre>';
				}
			}

			$data['iscomplete'] = true;			
		}
	}
	
	
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/slinker')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	
	
	
	public function rewrite($link) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));

		$url = '';

		$data = array();

		parse_str($url_info['query'], $data);

		foreach ($data as $key => $value) {
			if (isset($data['route'])) {
				if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . ($key . '=' . (int)$value) . "'");

					if ($query->num_rows && $query->row['keyword']) {
						$url .= '/' . $query->row['keyword'];

						unset($data[$key]);
					}
				} elseif ($key == 'path') {
					$categories = explode('_', $value);

					foreach ($categories as $category) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");

						if ($query->num_rows && $query->row['keyword']) {
							$url .= '/' . $query->row['keyword'];
						} else {
							$url = '';

							break;
						}
					}

					unset($data[$key]);
				}
			}
		}

		if ($url) {
			unset($data['route']);

			$query = '';

			if ($data) {
				foreach ($data as $key => $value) {
					$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((string)$value);
				}

				if ($query) {
					$query = '?' . str_replace('&', '&amp;', trim($query, '&'));
				}
			}

			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
		} else {
			return $link;
		}
	}
	
}