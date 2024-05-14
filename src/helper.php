<?php
// 应用公共文件
	/**
	* 网络请求 获取带请求头的方法
	* @author   Renshuo
	*  GET
	* //设置头文件的信息作为数据流输出
		curl_setopt($curl,CURLOPT_HEADER,1);
	* @version  
	* @datetime 
	* @param    [string]          $url  [请求url]
	* @param    [array]           $data [发送数据]
	* @return   [mixed]                 [请求返回数据]
	*/
    function HttpHeaderRequest($url, $data)
    {
    	// dd($data);
    	$url = $url.'?'.http_build_query($data);
    	// dd($url);
		//初始化
		$curl  =  curl_init();
		//设置抓取的url
		curl_setopt($curl,CURLOPT_URL,$url);
		//设置头文件的信息作为数据流输出
		curl_setopt($curl,CURLOPT_HEADER,1);
		//设置获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//执行命令
		$data = curl_exec($curl);

		// 检查是否有错误发生
		if (curl_errno($curl)) {
			// 如果发生错误，打印错误信息
			Log::record('cURL Error: '.curl_error($curl));
		} else {
			// 如果成功，处理响应数据...
			// 获取头部信息
			$headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
			$header = substr($data, 0, $headerSize);
			$body = substr($data, $headerSize);
			return json_decode($body, 1);
		}


		//关闭URL请求
		curl_close($curl);
		
	}

	    /**
     * 网络请求
     * @author   
     * 
     * @version  
     * @datetime 
     * @param    [string]          $url  [请求url]
     * @param    [array]           $data [发送数据]
     * @return   [mixed]                 [请求返回数据]
     */
    function HttpRequest($url, $data=[])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $body_string = '';
        if(is_array($data) && 0 < count($data))
        {
            foreach($data as $k => $v)
            {
                $body_string .= $k.'='.urlencode($v).'&';
            }
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body_string);
        }
        $headers = array('content-type: application/x-www-form-urlencoded;charset=UTF-8');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $reponse = curl_exec($ch);
        if(curl_errno($ch))
        {
            return curl_error($ch);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if(200 !== $httpStatusCode)
            {
                return false;
            }
        }
        curl_close($ch);
        return json_decode($reponse, true);
    }