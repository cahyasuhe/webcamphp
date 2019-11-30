<?php
    //masukan nomor token Anda di sini
    define('TOKEN','707671406:AAGp1kszeV2VjTvc591xzaP2t4erWntsW9s');
    
    //Fungsi untuk Penyederhanaan kirim <span class="tooltipsall classtoolTips15" style="border-bottom:2px dotted #888;">perintah</span> dari URI API Telegram
    function BotKirim($perintah){
        return 'https://api.telegram.org/bot'.TOKEN.'/'.$perintah;
    }

    /* Fungsi untuk mengirim "<span class="tooltipsall classtoolTips15" style="border-bottom:2px dotted #888;">perintah</span>" ke Telegram
     * <span class="tooltipsall classtoolTips15" style="border-bottom:2px dotted #888;">Perintah</span> tersebut bisa berupa
     *  -SendMessage = Untuk mengirim atau membalas pesan
     *  -SendSticker = Untuk mengirim pesan
     *  -Dan sebagainya, Anda bisa memm
     * 
     * Adapun dua fungsi di sini yakni pertama menggunakan
     * stream dan yang kedua menggunkan curl
     * 
     * */
    function KirimPerintahStream($perintah,$data){
         $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context  = stream_context_create($options);
        $result = file_get_contents(BotKirim($perintah), false, $context);
        return $result;
    }
    
    function KirimPerintahCurl($perintah,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,BotKirim($perintah));
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $kembali = curl_exec ($ch);
        curl_close ($ch);

        return $kembali;
    }

    /*  <span class="tooltipsall classtoolTips15" style="border-bottom:2px dotted #888;">Perintah</span> untuk mendapatkan Update dari Api Telegram.
     *  Fungsi ini menjadi penting karena kita menggunakan metode "Long-Polling".
     *  Jika Anda menggunakan webhooks, fungsi ini tidaklah diperlukan lagi.
     */
     
    function DapatkanUpdate($offset) 
    {
        //kirim ke Bot
        $url = BotKirim("getUpdates")."?offset=".$offset;
        //dapatkan hasilnya berupa JSON
        $kirim = file_get_contents($url);
        //kemudian decode JSON tersebut
        $hasil = json_decode($kirim, true);
        if ($hasil["ok"]==1)
            {
                /* Jika hasil["ok"] bernilai satu maka berikan isi JSONnya.
                 * Untuk dipergunakan mengirim <span class="tooltipsall classtoolTips15" style="border-bottom:2px dotted #888;">perintah</span> balik ke Telegram
                 */
                return $hasil["result"];
            }
        else
            {   /* Jika tidak maka kosongkan hasilnya.
                 * Hasil harus berupa Array karena kita menggunakan JSON.
                 */
                return array();
            }
    }

    function JalankanBot()
        {
            $update_id  = 0; //mula-mula tepatkan nilai offset pada nol
         
            //cek file apakah terdapat file "last_update_id"
            if (file_exists("last_update_id")) {
                //jika ada, maka baca offset tersebut dari file "last_update_id"
                $update_id = (int)file_get_contents("last_update_id");
            }
            //baca JSON dari bot, cek dan dapatkan pembaharuan JSON nya
            $updates = DapatkanUpdate($update_id);
                    
            foreach ($updates as $message)
            {
                    $update_id = $message["update_id"];;
                    $message_data = $message["message"];
                    
                    //jika terdapat text dari Pengirim
                     if (isset($message_data["text"])) {
                            $chatid = $message_data["chat"]["id"];
                            $message_id = $message_data["message_id"];
                            $text = $message_data["text"];
                            
                            $data = array(
                                'chat_id' => $chatid,
                                'text'=> 'tes balas halo',
                                'parse_mode'=>'Markdown',
                                'reply_to_message_id' => $message_id
                            );
                            //kita gunakan Kirim <span class="tooltipsall classtoolTips15" style="border-bottom:2px dotted #888;">Perintah</span> menggunakan metode Curl
                            KirimPerintahCurl('sendMessage',$data);
                        }
                    
            }
            //tulis dan tandai updatenya yang nanti digunakan untuk nilai offset
            file_put_contents("last_update_id", $update_id + 1);
        }
        
    while(true){
        sleep(2); //beri jedah 2 detik
        JalankanBot();
    }