<?php
class Fresh_Local_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function cartExist($product){
		$quote = Mage::getSingleton('checkout/session')->getQuote();

		$foundInCart = 0;
		foreach($quote->getAllVisibleItems() as $item){
			//$foundInCart = $item->getData();
			////die();
			if($item->getProductId() == $product->getId()){
				$foundInCart = $item->getQty();
				break;
			}
		}
		
		return $foundInCart;
	}
	
	public function getAddToCartUrl($product, $qty = 1){
		if($product->getId()){
			$key =  Mage::getSingleton('core/session')->getFormKey();
			
			return Mage::getUrl('freshlocal/checkout_cart/ajaxadd', array('product' => $product->getId(), 'id' => $product->getId(), 'qty' => $qty, 'form_key' => $key));
		}
		return '';
		//$this->getUrl('freshlocal/checkout_cart/ajaxadd', array('id' => $_product->getId(), '_secure' => $this->_isSecure()))
		//$this->_redirect('checkout/cart/add', array('product'=>$product, 'form_key'=>$key));
	}
	
	
	public function getRemoveFromCartUrl($product, $qty = 1){
		if($product->getId()){
			$key =  Mage::getSingleton('core/session')->getFormKey();
			
			return Mage::getUrl('freshlocal/checkout_cart/ajaxupdateitem', array('product' => $product->getId(), 'id' => $product->getId(), 'qty' => $qty, 'form_key' => $key));
		}
		return '';
		//$this->getUrl('freshlocal/checkout_cart/ajaxadd', array('id' => $_product->getId(), '_secure' => $this->_isSecure()))
		//$this->_redirect('checkout/cart/add', array('product'=>$product, 'form_key'=>$key));
	}
	
	public function getQuickViewUrl($product){
		if($product->getId()){
			return Mage::getUrl('freshlocal/product/quickview', array('id' => $product->getId()));
		}
	}
	
	public function getFavoritesUrl($product){
		if($product->getId()){
			$key =  Mage::getSingleton('core/session')->getFormKey();
			return Mage::getUrl('freshlocal/wishlist_index/ajaxadd', array('product' => $product->getId(), 'form_key' => $key));
		}
	}
	
	public function getCountryCodeOptions(){
		return array(
			array("value" => "", "label" => ""),
			array("value" => "AF", "label" => "Afghanistan (+93)"),
			array("value" => "AX", "label" => "Aland Islands"),
			array("value" => "AL", "label" => "Albania (+355)"),
			array("value" => "DZ", "label" => "Algeria (+213)"),
			array("value" => "AS", "label" => "American Samoa (+1 684)"),
			array("value" => "AD", "label" => "Andorra (+376)"),
			array("value" => "AO", "label" => "Angola (+244)"),
			array("value" => "AI", "label" => "Anguilla (+1 264)"),
			array("value" => "AQ", "label" => "Antarctica"),
			array("value" => "AG", "label" => "Antigua and Barbuda (+1268)"),
			array("value" => "AR", "label" => "Argentina (+54)"),
			array("value" => "AM", "label" => "Armenia (+374)"),
			array("value" => "AW", "label" => "Aruba (+297)"),
			array("value" => "AU", "label" => "Australia (+61)"),
			array("value" => "AT", "label" => "Austria (+43)"),
			array("value" => "AZ", "label" => "Azerbaijan (+994)"),
			array("value" => "BS", "label" => "Bahamas (+1 242)"),
			array("value" => "BH", "label" => "Bahrain (+973)"),
			array("value" => "BD", "label" => "Bangladesh (+880)"),
			array("value" => "BB", "label" => "Barbados (+1 246)"),
			array("value" => "BY", "label" => "Belarus (+375)"),
			array("value" => "BE", "label" => "Belgium (+32)"),
			array("value" => "BZ", "label" => "Belize (+501)"),
			array("value" => "BJ", "label" => "Benin (+229)"),
			array("value" => "BM", "label" => "Bermuda (+1 441)"),
			array("value" => "BT", "label" => "Bhutan (+975)"),
			array("value" => "BO", "label" => "Bolivia, Plurinational State of (+591)"),
			array("value" => "BA", "label" => "Bosnia and Herzegovina (+387)"),
			array("value" => "BW", "label" => "Botswana (+267)"),
			array("value" => "BV", "label" => "Bouvet Island"),
			array("value" => "BR", "label" => "Brazil (+55)"),
			array("value" => "IO", "label" => "British Indian Ocean Territory (+246)"),
			array("value" => "VG", "label" => "British Virgin Islands"),
			array("value" => "BN", "label" => "Brunei Darussalam (+673)"),
			array("value" => "BG", "label" => "Bulgaria (+359)"),
			array("value" => "BF", "label" => "Burkina Faso (+226)"),
			array("value" => "BI", "label" => "Burundi (+257)"),
			array("value" => "KH", "label" => "Cambodia (+855)"),
			array("value" => "CM", "label" => "Cameroon (+237)"),
			array("value" => "CA", "label" => "Canada (+1)"),
			array("value" => "CV", "label" => "Cape Verde (+238)"),
			array("value" => "KY", "label" => "Cayman Islands (+ 345)"),
			array("value" => "CF", "label" => "Central African Republic (+236)"),
			array("value" => "TD", "label" => "Chad (+235)"),
			array("value" => "CL", "label" => "Chile (+56)"),
			array("value" => "CN", "label" => "China (+86)"),
			array("value" => "CX", "label" => "Christmas Island (+61)"),
			array("value" => "CC", "label" => "Cocos (Keeling) Islands (+61)"),
			array("value" => "CO", "label" => "Colombia (+57)"),
			array("value" => "KM", "label" => "Comoros (+269)"),
			array("value" => "CG", "label" => "Congo, The Democratic Republic of the (+243)"),
			array("value" => "CD", "label" => "Congo (+242)"),
			array("value" => "CK", "label" => "Cook Islands (+682)"),
			array("value" => "CR", "label" => "Costa Rica (+506)"),
			array("value" => "CI", "label" => "Cote d'Ivoire (+225)"),
			array("value" => "HR", "label" => "Croatia (+385)"),
			array("value" => "CU", "label" => "Cuba (+53)"),
			array("value" => "CY", "label" => "Cyprus (+357)"),
			array("value" => "CZ", "label" => "Czech Republic (+420)"),
			array("value" => "DK", "label" => "Denmark (+45)"),
			array("value" => "DJ", "label" => "Djibouti (+253)"),
			array("value" => "DM", "label" => "Dominica (+1 767)"),
			array("value" => "DO", "label" => "Dominican Republic (+1 809) (+1 829) (+1 849)"),
			array("value" => "EC", "label" => "Ecuador (+593)"),
			array("value" => "EG", "label" => "Egypt (+20)"),
			array("value" => "SV", "label" => "El Salvador (+503)"),
			array("value" => "GQ", "label" => "Equatorial Guinea (+240)"),
			array("value" => "ER", "label" => "Eritrea (+291)"),
			array("value" => "EE", "label" => "Estonia (+372)"),
			array("value" => "ET", "label" => "Ethiopia (+251)"),
			array("value" => "FK", "label" => "Falkland Islands (Malvinas) (+500)"),
			array("value" => "FO", "label" => "Faroe Islands (+298)"),
			array("value" => "FJ", "label" => "Fiji (+679)"),
			array("value" => "FI", "label" => "Finland (+358)"),
			array("value" => "FR", "label" => "France (+33)"),
			array("value" => "GF", "label" => "French Guiana (+594)"),
			array("value" => "PF", "label" => "French Polynesia (+689)"),
			array("value" => "TF", "label" => "French Southern Territories"),
			array("value" => "GA", "label" => "Gabon (+241)"),
			array("value" => "GM", "label" => "Gambia (+220)"),
			array("value" => "GE", "label" => "Georgia (+995)"),
			array("value" => "DE", "label" => "Germany (+49)"),
			array("value" => "GH", "label" => "Ghana (+233)"),
			array("value" => "GI", "label" => "Gibraltar (+350)"),
			array("value" => "GR", "label" => "Greece (+30)"),
			array("value" => "GL", "label" => "Greenland (+299)"),
			array("value" => "GD", "label" => "Grenada (+1 473)"),
			array("value" => "GP", "label" => "Guadeloupe (+590)"),
			array("value" => "GU", "label" => "Guam (+1 671)"),
			array("value" => "GT", "label" => "Guatemala (+502)"),
			array("value" => "GG", "label" => "Guernsey (+44)"),
			array("value" => "GN", "label" => "Guinea (+224)"),
			array("value" => "GW", "label" => "Guinea-Bissau (+245)"),
			array("value" => "GY", "label" => "Guyana (+595)"),
			array("value" => "HT", "label" => "Haiti (+509)"),
			array("value" => "HM", "label" => "Heard & McDonald Islands"),
			array("value" => "HN", "label" => "Honduras (+504)"),
			array("value" => "HK", "label" => "Hong Kong (+852)"),
			array("value" => "HU", "label" => "Hungary (+36)"),
			array("value" => "IS", "label" => "Iceland (+354)"),
			array("value" => "IN", "label" => "India (+91)"),
			array("value" => "ID", "label" => "Indonesia (+62)"),
			array("value" => "IR", "label" => "Iran, Islamic Republic of (+98)"),
			array("value" => "IQ", "label" => "Iraq (+964)"),
			array("value" => "IE", "label" => "Ireland (+353)"),
			array("value" => "IM", "label" => "Isle of Man (+44)"),
			array("value" => "IL", "label" => "Israel (+972)"),
			array("value" => "IT", "label" => "Italy (+39)"),
			array("value" => "JM", "label" => "Jamaica (+1 876)"),
			array("value" => "JP", "label" => "Japan (+81)"),
			array("value" => "JE", "label" => "Jersey (+44)"),
			array("value" => "JO", "label" => "Jordan (+962)"),
			array("value" => "KZ", "label" => "Kazakhstan (+7 7)"),
			array("value" => "KE", "label" => "Kenya (+254)"),
			array("value" => "KI", "label" => "Kiribati (+686)"),
			array("value" => "KW", "label" => "Kuwait (+965)"),
			array("value" => "KG", "label" => "Kyrgyzstan (+996)"),
			array("value" => "LA", "label" => "Lao People's Democratic Republic (+856)"),
			array("value" => "LV", "label" => "Latvia (+371)"),
			array("value" => "LB", "label" => "Lebanon (+961)"),
			array("value" => "LS", "label" => "Lesotho (+266)"),
			array("value" => "LR", "label" => "Liberia (+231)"),
			array("value" => "LY", "label" => "Libyan Arab Jamahiriya (+218)"),
			array("value" => "LI", "label" => "Liechtenstein (+423)"),
			array("value" => "LT", "label" => "Lithuania (+370)"),
			array("value" => "LU", "label" => "Luxembourg (+352)"),
			array("value" => "MO", "label" => "Macao (+853)"),
			array("value" => "MK", "label" => "Macedonia, The Former Yugoslav Republic of (+389)"),
			array("value" => "MG", "label" => "Madagascar (+261)"),
			array("value" => "MW", "label" => "Malawi (+265)"),
			array("value" => "MY", "label" => "Malaysia (+60)"),
			array("value" => "MV", "label" => "Maldives (+960)"),
			array("value" => "ML", "label" => "Mali (+223)"),
			array("value" => "MT", "label" => "Malta (+356)"),
			array("value" => "MH", "label" => "Marshall Islands (+692)"),
			array("value" => "MQ", "label" => "Martinique (+596)"),
			array("value" => "MR", "label" => "Mauritania (+222)"),
			array("value" => "MU", "label" => "Mauritius (+230)"),
			array("value" => "YT", "label" => "Mayotte (+262)"),
			array("value" => "MX", "label" => "Mexico (+52)"),
			array("value" => "FM", "label" => "Micronesia, Federated States of (+691)"),
			array("value" => "MD", "label" => "Moldova, Republic of (+373)"),
			array("value" => "MC", "label" => "Monaco (+377)"),
			array("value" => "MN", "label" => "Mongolia (+976)"),
			array("value" => "ME", "label" => "Montenegro (+382)"),
			array("value" => "MS", "label" => "Montserrat (+1664)"),
			array("value" => "MA", "label" => "Morocco (+212)"),
			array("value" => "MZ", "label" => "Mozambique (+258)"),
			array("value" => "MM", "label" => "Myanmar (+95)"),
			array("value" => "NA", "label" => "Namibia (+264)"),
			array("value" => "NR", "label" => "Nauru (+674)"),
			array("value" => "NP", "label" => "Nepal (+977)"),
			array("value" => "NL", "label" => "Netherlands (+31)"),
			array("value" => "AN", "label" => "Netherlands Antilles (+599)"),
			array("value" => "NC", "label" => "New Caledonia (+687)"),
			array("value" => "NZ", "label" => "New Zealand (+64)"),
			array("value" => "NI", "label" => "Nicaragua (+505)"),
			array("value" => "NE", "label" => "Niger (+227)"),
			array("value" => "NG", "label" => "Nigeria (+234)"),
			array("value" => "NU", "label" => "Niue (+683)"),
			array("value" => "NF", "label" => "Norfolk Island (+672)"),
			array("value" => "MP", "label" => "Northern Mariana Islands (+1 670)"),
			array("value" => "KP", "label" => "North Korea (+82)"),
			array("value" => "NO", "label" => "Norway (+47)"),
			array("value" => "OM", "label" => "Oman (+968)"),
			array("value" => "PK", "label" => "Pakistan (+92)"),
			array("value" => "PW", "label" => "Palau (+680)"),
			array("value" => "PS", "label" => "Palestinian Territory, Occupied (+970)"),
			array("value" => "PA", "label" => "Panama (+507)"),
			array("value" => "PG", "label" => "Papua New Guinea (+675)"),
			array("value" => "PY", "label" => "Paraguay (+595)"),
			array("value" => "PE", "label" => "Peru (+51)"),
			array("value" => "PH", "label" => "Philippines (+63)"),
			array("value" => "PN", "label" => "Pitcairn (+872)"),
			array("value" => "PL", "label" => "Poland (+48)"),
			array("value" => "PT", "label" => "Portugal (+351)"),
			array("value" => "PR", "label" => "Puerto Rico (+1 787) (+1 939)"),
			array("value" => "QA", "label" => "Qatar (+974)"),
			array("value" => "RE", "label" => "Reunion (+262)"),
			array("value" => "RO", "label" => "Romania (+40)"),
			array("value" => "RU", "label" => "Russia (+7)"),
			array("value" => "RW", "label" => "Rwanda (+250)"),
			array("value" => "BL", "label" => "Saint Barthelemy (+590)"),
			array("value" => "SH", "label" => "Saint Helena, Ascension and Tristan Da Cunha (+290)"),
			array("value" => "KN", "label" => "Saint Kitts and Nevis (+1 869)"),
			array("value" => "LC", "label" => "Saint Lucia (+1 758)"),
			array("value" => "MF", "label" => "Saint Martin (+590)"),
			array("value" => "PM", "label" => "Saint Pierre and Miquelon (+508)"),
			array("value" => "WS", "label" => "Samoa (+685)"),
			array("value" => "SM", "label" => "San Marino (+378)"),
			array("value" => "ST", "label" => "Sao Tome and Principe (+239)"),
			array("value" => "SA", "label" => "Saudi Arabia (+966)"),
			array("value" => "SN", "label" => "Senegal (+221)"),
			array("value" => "RS", "label" => "Serbia (+381)"),
			array("value" => "SC", "label" => "Seychelles (+248)"),
			array("value" => "SL", "label" => "Sierra Leone (+232)"),
			array("value" => "SG", "label" => "Singapore (+65)"),
			array("value" => "SK", "label" => "Slovakia (+421)"),
			array("value" => "SI", "label" => "Slovenia (+386)"),
			array("value" => "SB", "label" => "Solomon Islands (+677)"),
			array("value" => "SO", "label" => "Somalia (+252)"),
			array("value" => "ZA", "label" => "South Africa (+27)"),
			array("value" => "GS", "label" => "South Georgia and the South Sandwich Islands (+500)"),
			array("value" => "KR", "label" => "South Korea (+82)"),
			array("value" => "ES", "label" => "Spain (+34)"),
			array("value" => "LK", "label" => "Sri Lanka (+94)"),
			array("value" => "VC", "label" => "Saint Vincent and the Grenadines (+1 784)"),
			array("value" => "SD", "label" => "Sudan (+249)"),
			array("value" => "SR", "label" => "Suriname (+597)"),
			array("value" => "SJ", "label" => "Svalbard and Jan Mayen (+47)"),
			array("value" => "SZ", "label" => "Swaziland (+268)"),
			array("value" => "SE", "label" => "Sweden (+46)"),
			array("value" => "CH", "label" => "Switzerland (+41)"),
			array("value" => "SY", "label" => "Syrian Arab Republic (+963)"),
			array("value" => "TW", "label" => "Taiwan, Province of China (+886)"),
			array("value" => "TJ", "label" => "Tajikistan (+992)"),
			array("value" => "TZ", "label" => "Tanzania (+255)"),
			array("value" => "TH", "label" => "Thailand (+66)"),
			array("value" => "TL", "label" => "Timor-Leste (+670)"),
			array("value" => "TG", "label" => "Togo (+228)"),
			array("value" => "TK", "label" => "Tokelau (+690)"),
			array("value" => "TO", "label" => "Tonga (+676)"),
			array("value" => "TT", "label" => "Trinidad and Tobago (+1 868)"),
			array("value" => "TN", "label" => "Tunisia (+216)"),
			array("value" => "TR", "label" => "Turkey (+90)"),
			array("value" => "TM", "label" => "Turkmenistan (+993)"),
			array("value" => "TC", "label" => "Turks and Caicos Islands (+1 649)"),
			array("value" => "TV", "label" => "Tuvalu (+688)"),
			array("value" => "UG", "label" => "Uganda (+256)"),
			array("value" => "UA", "label" => "Ukraine (+380)"),
			array("value" => "AE", "label" => "United Arab Emirates (+971)"),
			array("value" => "GB", "label" => "United Kingdom (+44)"),
			array("value" => "US", "label" => "United States (+1)"),
			array("value" => "UY", "label" => "Uruguay (+598)"),
			array("value" => "UM", "label" => "Virgin Islands, British (+1 284)"),
			array("value" => "VI", "label" => "Virgin Islands, U.S. (+1 340)"),
			array("value" => "UZ", "label" => "Uzbekistan (+998)"),
			array("value" => "VU", "label" => "Vanuatu (+678)"),
			array("value" => "VA", "label" => "Holy See, Vatican City State (+379)"),
			array("value" => "VE", "label" => "Venezuela, Bolivarian Republic of (+58)"),
			array("value" => "VN", "label" => "Viet Nam (+84)"),
			array("value" => "WF", "label" => "Wallis and Futuna (+681)"),
			array("value" => "EH", "label" => "Western Sahara"),
			array("value" => "YE", "label" => "Yemen (+967)"),
			array("value" => "ZM", "label" => "Zambia (+260)"),
			array("value" => "ZW", "label" => "Zimbabwe (+263)")
		);
	}
}

