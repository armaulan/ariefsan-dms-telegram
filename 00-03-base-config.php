<?php
# https://ariefsan.crewbasproject.my.id/telegram/00-03-base-config.php

function config() {
    return array (
            "key-sck4500" => "5546536525:AAEDiQayLE1klZe1SWj-dEZ6TONKyVQU26s",
      		"key-zafbot" => "5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c",
      		"key-wa-bas" => "MffqOP2nk3ZKxUhWdqoG6zGfOn9pWNlsPi2yhZeMWR26iJ3E3kf1BgUTlcbnh97r",
      		"key-whacenter-1" => "25eae1be21bc28fb49aec299354a1ce1",
      	
            #Domain
            "domain2" => "https://tlgrm.iccgt.my.id/ariefsan/telegram/",
      		
      		#Receiver Id Telegram
      		"id-tele-group-server" => "-620429518",
            "id-tele-group-data" => "-1001530151316",
      		"id-wa-arya" => "6282180603613",
      		"id-wa-fighter" => "6281319152872-1588651135",
      		"id-wa-group-fa" => "120363107340166301",
      		"id-etrademode" => "120363162930815827",
      		"id-wa-group-agent" => "6281287605857-1556791477",
      		"id-wa-group-dist" => "6281287605857-1543142572",
      		"id-wa-group-qdms" => "120363022968328005",
      		"id-wa-group-crew" => "6281212500170-1596252861",
      		"id-wa-riky" => "6285694787887",
      		"id-wa-yayat" => "6281132214733",    
                  
      		#function helper
            "time_checker_night" => function ($current_time) {
                                        if ($current_time >= strtotime('01:00') &&  $current_time <= strtotime('05:00')) {
                                            exit();
                                        } 
             							},
            
            "time_checker_dayname"  => function ($current_time, $day1, $day2) {
                                        if ($current_time == $day1 ||  $current_time == $day2) {
                                            echo "works";
                                            exit();
                                        } 
             							},
            
            "time_stopper" => function ($current_time, $start, $end) {
                                        if ($current_time >= strtotime($start) &&  $current_time <= strtotime($end)) {
                                            exit();
                                        } 
            							},
      
            "telegramSendMessage" => function ($key, $msg, $id) {
                           				$ch = curl_init(); 
    									curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $key . "/sendMessage?chat_id=" .$id. "&text=" . urlencode($msg));
    									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    									$output = curl_exec($ch); 
    									curl_close($ch); 
            							},
      
            "whatsappSendMessage" => function ($key, $msg, $id, $isGroup) {
                                        echo "test";
                           				$ch = curl_init(); 
    									curl_setopt($ch, CURLOPT_URL, "https://jogja.wablas.com/api/send-message?phone=" . $id . "&message=" . urlencode($msg). "&token=" . $key ."&isGroup=" . $isGroup);
    									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    									$output = curl_exec($ch); 
    									var_dump($output);
    									curl_close($ch); 
            							},
      
            "waFooter" => function ($key, $button, $id, $isGroup) {
              
                           				$curl = curl_init();
    									$payload = [
    										"data" => [
        												[
            												'phone' => $id,
            												'message' => [
                															'header' => [
                    															'type' => 'text',
                    															'content' => 'Additional Information',
                																],
                															'buttons' => $button,
                															'content' => 'list-command',
                															'footer' => 'Powered by CDS Team',
            															],
            												'isGroup' => $isGroup,
            												'source' => 'postman',
        													]
    													]
													];
              							
              							curl_setopt($curl, CURLOPT_HTTPHEADER,
    										array(
        										"Authorization: " . $key,
        										"Content-Type: application/json"
    												)
												);
              							
              							curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, $isGroup);
                                        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
                                        curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/v2/send-button");
                                        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

										$result = curl_exec($curl);
										curl_close($curl);
              
            							},      
      
      		"whatsappSendDocs" => function ($key, $phone, $filePath, $isGroup) {
                
                	$curl = curl_init();
                  	# $token = "";
                  	$data = [
                      'phone' => $phone,
                      'document' => $filePath,
                      'isGroup' => $isGroup,
                  		];
                  
                  	curl_setopt($curl, CURLOPT_HTTPHEADER,
                      array(
                          "Authorization: $key",
                      )
                  );
                  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                  curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/send-document");
                  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

                  $result = curl_exec($curl);
                  echo $result;
                  curl_close($curl);

                
                },
         
      		
     		"whatsappSendImg" => function ($key, $phone, $filePath, $isGroup, $caption) {
                	
              $curl = curl_init();
                  	# $token = "";
                  	$data = [
                      'phone' => $phone,
                      'image' => $filePath,
                      'isGroup' => $isGroup,
                      'caption' => $caption,
                  		];
                  
                  	curl_setopt($curl, CURLOPT_HTTPHEADER,
                      array(
                          "Authorization: $key",
                      )
                  );
                  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                  curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/send-image");
                  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

                  $result = curl_exec($curl);
                  curl_close($curl);
            
            },
      
   			# Whacenter
     		"whacenterSendGroupMessage" => function ($key, $groupName, $message) {
                  $ch = curl_init();
                  $device_id = $key;
              
                  curl_setopt($ch, CURLOPT_URL,"https://app.whacenter.com/api/sendGroup");
                  curl_setopt($ch, CURLOPT_POST, 1);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, 
                                  http_build_query(array(
                                      'device_id' => $device_id,
                                      'group' => $groupName,
                                      'message' => $message
                                  )));

                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  $server_output = curl_exec($ch);
            },
      		# End whacenterSendGroupMessage
      
   			# Whacenter
     		"whacenterSendGroupDoc" => function ($key, $groupName, $message, $file) {
                  $ch = curl_init();
                  $device_id = $key;
              
                  curl_setopt($ch, CURLOPT_URL,"https://app.whacenter.com/api/sendGroup");
                  curl_setopt($ch, CURLOPT_POST, 1);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, 
                                  http_build_query(array(
                                      'device_id' => $device_id,
                                      'group' => $groupName,
                                      'message' => $message,
                                      'file' => $file
                                  )));

                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  $server_output = curl_exec($ch);
            },
      		# End whacenterSendGroupMessage
      
      "whacentersenddoc" => function ($key, $number, $message, $file) {
                  $ch = curl_init();
                  $device_id = $key;
              
                  curl_setopt($ch, CURLOPT_URL,"https://app.whacenter.com/api/send");
                  curl_setopt($ch, CURLOPT_POST, 1);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, 
                                  http_build_query(array(
                                      'device_id' => $device_id,
                                      'number' => $number,
                                      'message' => $message,
                                      'file' => $file
                                  )));

                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  $server_output = curl_exec($ch);
            },
      		# End whacentersenddoc
      
           # Whacenter Send Message
     		"whacenterSendMessage" => function ($key, $number, $message) {
                  $ch = curl_init();
                  $device_id = $key;
              
                  curl_setopt($ch, CURLOPT_URL,"https://app.whacenter.com/api/send");
                  curl_setopt($ch, CURLOPT_POST, 1);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, 
                                  http_build_query(array(
                                      'device_id' => $device_id,
                                      'number' => $number,
                                      'message' => $message
                                  )));

                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  $server_output = curl_exec($ch);
            },
      		# End whacenterSendGroupMessage
      
            
    );    
}
