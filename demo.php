<?php
require '/wordpress/wp-load.php';
update_option('blogname','Shenanigans');
update_option('blogdescription','Shenanigans merch');
update_option('woocommerce_currency','GBP');
update_option('woocommerce_default_country','GB');
update_option('woocommerce_onboarding_profile',array('completed'=>true));
update_option('woocommerce_store_pages',array());
update_option('woocommerce_coming_soon','no');
update_option('woocommerce_task_list_hidden','yes');
WC_Install::create_pages();
switch_theme('shenanigans-merch');
$p=new WC_Product_Variable();
$p->set_name('The Shenanigans Tee.');
$p->set_slug('shenanigans-tee');
$p->set_status('publish');
$p->set_short_description('A little Shenanigans for your everyday wardrobe.');
$p->set_description('<p>Black T-shirt with the Shenanigans record print.</p><p>This is a demonstration product. Artwork, price and sizes are placeholders.</p>');
$p->set_reviews_allowed(false);
$a=new WC_Product_Attribute();
$a->set_name('Size');$a->set_options(array('S','M','L','XL','XXL'));$a->set_visible(true);$a->set_variation(true);
$p->set_attributes(array($a));
$p->set_default_attributes(array('size'=>'M'));
$p->save();
foreach(array('S','M','L','XL','XXL') as $size){
 $v=new WC_Product_Variation();$v->set_parent_id($p->get_id());$v->set_attributes(array('size'=>$size));
 $v->set_regular_price('25');$v->set_status('publish');$v->set_stock_status('instock');$v->save();
}
require_once ABSPATH.'wp-admin/includes/image.php';
$file=wp_upload_bits('shenanigans-demo-tee.jpg',null,file_get_contents(get_theme_file_path('assets/demo-shirt.jpg')));
if($file['error'])throw new Exception($file['error']);
$id=wp_insert_attachment(array('post_title'=>'Shenanigans demo T-shirt','post_mime_type'=>'image/jpeg','post_status'=>'inherit'),$file['file'],$p->get_id());
wp_update_attachment_metadata($id,wp_generate_attachment_metadata($id,$file['file']));
update_post_meta($id,'_wp_attachment_image_alt','Black Shenanigans T-shirt with cream wordmark and red vinyl print; demo artwork');
$p->set_image_id($id);$p->save();
WC_Product_Variable::sync($p->get_id());
update_option('sh_demo_product_id',$p->get_id());
update_option('show_on_front','page');
update_option('page_on_front',get_option('woocommerce_shop_page_id'));
file_put_contents('/wordpress/wp-content/mu-plugins/shenanigans-demo.php', '<?php
add_filter("woocommerce_available_payment_gateways","__return_empty_array");
add_action("wp_body_open",function(){echo "<div style=\"background:#22211e;color:#fff7e8;text-align:center;padding:8px;font:14px sans-serif\">WooCommerce demo · placeholder product · checkout disabled</div>";});
');
foreach(array('woocommerce/product-image-gallery','woocommerce/product-price','woocommerce/product-summary','woocommerce/add-to-cart-form','woocommerce/product-details') as $block){
 if(!WP_Block_Type_Registry::get_instance()->is_registered($block))throw new Exception('Missing block '.$block);
}
echo 'DEMO_READY product='.$p->get_id().' variations='.count($p->get_children()).' theme='.get_stylesheet();
