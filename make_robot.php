<?php  
	header ("Content-type: image/jpeg");  
	// Вспомогательные
	function ImageFlip($img)
	{
	    	$width  = imagesx($img);
	    	$height = imagesy($img);
	    	$dest   = imagecreate($width, $height);
    
            	for($i=0;$i<$width;$i++){
                imagecopy($dest, $img, ($width - $i - 1), 0, $i, 0, 1, $height);
            	}
            	
    		return $dest;
	}
	function ImageFlipAndMerge($img)
	{
		$width  = imagesx($img) * 2;
	    	$height = imagesy($img);
	    	$dest   = imagecreate($width, $height);

		$img2 = ImageFlip($img);
		
		ImageCopyMerge($dest,$img,0,0,0,0,imagesx($img),imagesy($img),100);
		ImageCopyMerge($dest,$img2,imagesx($img),0,0,0,imagesx($img),imagesy($img),100);

		return $dest;	
	}
	// Функции возвращающие изображение сгенерированной части
	function head($back_color)
	{
		$w = rand(5,10);
		$h = rand(10,20);
	
		$im = ImageCreate ($w, $h);  
		
		$cr = rand(0,105);$cg = rand(0,105);$cb = rand(0,105);
		$black = ImageColorAllocate($im, 0, 0, 0);
		$mouth_c = ImageColorAllocate($im, 80+$cr, 80+$cg, 80+$cb);
		$eye_c = ImageColorAllocate($im, 150+$cr, 150+$cg, 150+$cb);

		ImageFilledRectangle($im,0,0,$w,$h,$black);
			
		// Генерация глаза
		$eye_w = rand(1,$w-1);
		$eye_h = rand(1,$w-1);
		$eye_x = rand($eye_w/2+2,$w-$eye_w/2-2);
		$eye_y = rand($eye_h/2+2,$h-$eye_h/2-3);
		if(rand(0,1)==0) // квадратный
		{
			ImageFilledRectangle($im,$eye_x-$eye_w/2,$eye_y-$eye_h/2,$eye_w,$eye_h,$eye_c);
			
		}
		else		// круглый
		{
			ImageFilledEllipse($im,$eye_x,$eye_y,$eye_w,$eye_h,$eye_c);
		}
		// И зрачок
		if($eye_w > 3) ImageSetPixel($im, $eye_x,$eye_y, $black);
		// Рот
		$mouth_w = rand(1,$w);
		$mouth_h = rand(1,2);
		if($h - $eye_y + $eye_h/2 -2 > $mouth_h) // проверяем уместится ли рот
		{
			$mouth_x = $w-$mouth_w;
			$mouth_y = rand($eye_y + $eye_h/2+1,$h-$mouth_h);
			ImageFilledRectangle($im,$mouth_x,$mouth_y,$mouth_x+$mouth_w,$mouth_y+$mouth_h,$mouth_c);
		}

		return ImageFlipAndMerge($im);
	}
	function body($back_color)
	{
		$w = rand(3,20);
		$h = rand(10,30);
	
		$im = ImageCreate ($w, $h);  

		$black = ImageColorAllocate($im, 0, 0, 0);

		ImageFilledRectangle($im,0,0,$w,$h,$black);

		return ImageFlipAndMerge($im);
	}
	function arm($back_color,$body_h)
	{
		$w = rand(2,7);
		$h = rand(10,$body_h);
	
		$im = ImageCreate($w, $h);  

		$black = ImageColorAllocate($im, 0, 0, 0);

		ImageFilledRectangle($im,0,0,$w,$h,$black);

		return $im;
	}
	function leg($back_color,$body_w)
	{
		$w = rand(2,$body_w/2-1);
		$h = rand(3,15);
	
		$im = ImageCreate($w, $h);  

		$black = ImageColorAllocate($im, 0, 0, 0);

		ImageFilledRectangle($im,0,0,$w,$h,$black);

		return $im;
	}
	//Функция соединяющая части робота
	function merge($dest,$robot)
	{
		$head_x = (imagesx($dest)-imagesx($robot['head']))/2;
		$head_y = 5;
		
		$body_x = (imagesx($dest)-imagesx($robot['body']))/2;
		$body_y = $head_y + imagesy($robot['head']) + 1;

		$arm1_x = $body_x - imagesx($robot['arm1']); 
		$arm1_y = $body_y + rand(0,imagesy($robot['arm1'])/3);
		$arm2_x = $body_x + imagesx($robot['body']); 
		$arm2_y = $arm1_y;
		
		$leg_dx = rand(1,imagesx($robot['body'])/2-imagesx($robot['leg1'])-1);
		$leg1_x = $body_x + $leg_dx;
		$leg1_y = $body_y + imagesy($robot['body']);
		$leg2_x = $body_x + imagesx($robot['body']) - imagesx($robot['leg1']) - $leg_dx;
		$leg2_y = $leg1_y;

		ImageCopyMerge($dest,$robot['head'],$head_x,$head_y,0,0,imagesx($robot['head']),imagesy($robot['head']),100);
		ImageCopyMerge($dest,$robot['body'],$body_x,$body_y,0,0,imagesx($robot['body']),imagesy($robot['body']),100);
		ImageCopyMerge($dest,$robot['arm1'],$arm1_x,$arm1_y,0,0,imagesx($robot['arm1']),imagesy($robot['arm1']),100);
		ImageCopyMerge($dest,$robot['arm2'],$arm2_x,$arm2_y,0,0,imagesx($robot['arm2']),imagesy($robot['arm2']),100);
		ImageCopyMerge($dest,$robot['leg1'],$leg1_x,$leg1_y,0,0,imagesx($robot['leg1']),imagesy($robot['leg1']),100);
		ImageCopyMerge($dest,$robot['leg2'],$leg2_x,$leg2_y,0,0,imagesx($robot['leg2']),imagesy($robot['leg2']),100);

		return $dest;
	}

	// Создание изображения. Уменьшенного и большого.
	$width = 60;
	$height = 100;
	$scale = 5;
	$large_width = $width*$scale;
	$large_height = $height*$scale;
	$im = ImageCreate ($width, $height)  
		or die ("Ошибка при создании мини-изображения");     
	$im_large = ImageCreate ($large_width, $large_height)     
		or die ("Ошибка при создании изображения"); 
    
	$back_color = ImageColorAllocate($im, 220, 220, 220);
 
	// Генерация робота
	
	$robot['head'] = head($back_color);
	$robot['body'] = body($back_color);
	$robot['arm1'] = arm($back_color, imagesy($robot['body']));
	$robot['arm2'] = ImageFlip($robot['arm1']);
	$robot['leg1'] = leg($back_color,imagesx($robot['body']));
	$robot['leg2'] = ImageFlip($robot['leg1']);

	// Соединение частей
	
	$im = merge($im,$robot);

	// Увеличение изображения
	ImageCopyResized($im_large,$im,0,0,0,0,$large_width,$large_height,$width,$height);

	ImageJpeg($im_large);  
?>
