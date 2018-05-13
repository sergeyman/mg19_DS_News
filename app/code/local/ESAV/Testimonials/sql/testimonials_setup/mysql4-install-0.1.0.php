<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table testimonials (
 testimonials_id int not null auto_increment,
 testimonial text not null,
 user_id int(10) UNSIGNED not null,
primary key(testimonials_id),
FOREIGN KEY (user_id) REFERENCES customer_entity(entity_id) ON UPDATE CASCADE ON DELETE CASCADE

)
ENGINE=InnoDB ;
		
SQLTEXT;

$installer->run($sql);
$installer->endSetup();
	 