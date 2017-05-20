<?php
class MCServerStatus {

    public  $server,$online, $motd, $online_players, $max_players,$error = "No Error";

    function __construct($url, $port = '25565') {

        $this->server = array(
            "url" => $url,
            "port" => $port
        );
		
        if ( $sock = @stream_socket_client('tcp://'.$url.':'.$port, $errno, $errstr, 1) ) {
            $this->online = true;
            fwrite($sock, "\xfe");
            $a = fread($sock, 2048);
            $a = str_replace("\x00", '', $a);
            $a = substr($a, 2);
            $data = explode("\xa7", $a);
            unset($a);
            fclose($sock);
			
            if (sizeof($data) == 3) {
                $this->motd = $data[0];
                $this->online_players = (int) $data[1];
                $this->max_players = (int) $data[2];
				
            }elseif(sizeof($data) != 1){

                $this->motd = $data[0];
                $x=1;
                $color=array(
                "§0"=>"000000", //Black
				"§1"=>"0000AA", //Dark blue
				"§2"=>"00AA00", //Dark green
				"§3"=>"00AAAA", //Dark aqua
				"§4"=>"AA0000", //Dark red
				"§5"=>"AA00AA", //Dark purple
				"§6"=>"FFAA00", //Gold
				"§7"=>"AAAAAA", //Gray
				"§8"=>"555555", //Dark gray
				"§9"=>"5555FF", //Blue
				"§a"=>"55FF55", //Green
				"§b"=>"55FFFF", //Aqua
				"§c"=>"FF5555", //Red
				"§d"=>"FF55FF", //Light purple
				"§e"=>"FFFF55", //Yellow
				"§f"=>"FFFFFF", //White
				);
                while($x != sizeof($data)-2){
                 $colorcode="§".substr($data[$x],0,1);
                 $colorcode=$color["$colorcode"];
                 $this->motd .="<color style='color:#$colorcode'>".substr($data[$x],1)."</color>";
                $x++;
				}
                $this->online_players = (int) $data[sizeof($data)];
                $this->max_players = (int) $data[sizeof($data)-1];

            }else{
            $this->error = "Unknown error";
		}

        }else{
            $this->online = false;
            $this->error = "Server Offline";
        }

    }

}
$connect=new MCServerStatus("localhost","25565");
print_r($connect);
?>