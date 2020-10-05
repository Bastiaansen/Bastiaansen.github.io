<?php

DEFINE("EMAIL_REGEXP", "/^[_A-z0-9-]+((\.|\+)[_A-z0-9-]+)*@[A-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,4})$/");

if(isset($_POST["swi_submit"])) {
  // Gegevens ophalen en controleren (data collecting and monitoring)
  $errors = check_input($deelnemer = get_post());

  
  try {
    $deelnemer["photo"] = uploadfile();
  }
  catch (Exception $e) {
    $errors["photo"] = $e->getMessage();
  }

  // Wijzigingen valideren en opslaan (validate and store changes)
  if(isset($errors)) {
    print_form($deelnemer, $errors); 
  } else {
    $mailtext = "lastname=".$deelnemer["lastname"]."\n"
               ."firstname=".$deelnemer["firstname"]."\n"
               ."gender=".(($deelnemer["gender"] == "m") ? "male" : "female")."\n"
               ."email=".$deelnemer["email"]."\n"
               ."institute=".$deelnemer["institute"]."\n"
		."supervisors=".$deelnemer["supervisors"]."\n"
               ."presentation=".(($deelnemer["presentation"] == "tp") ? "talk and poster" :   ( ($deelnemer["presentation"] == "t") ?   "talk" :  "poster"))."\n"
   ."review=".(($deelnemer["review"] == "y") ? "yes" : "no")."\n"

              ."years in office=".$deelnemer["office"]."\n"
               ."vega=".$deelnemer["vega"]."\n"      
               ."roommate=".$deelnemer["roommate"]."\n"
      		."research=".$deelnemer["research"]."\n"
               ."remarks=".$deelnemer["zremarks"]."\n"
  ."useragreement=".(($deelnemer["useragreement"] == "a") ? "agree" : "decline")."\n";

   // $headers = "From: ".$deelnemer["email"]."\r\nX-mailer: PHP/".phpversion();
	mail_attachment($deelnemer["photo"], $path, "ndns.phddays@gmail.com", $deelnemer["email"], $deelnemer["lastname"], $deelnemer["email"], "PhD registration.", $mailtext);
    //mail("ndns.phddays@gmail.com", "PhD day registration.", $mailtext, $headers)&&email_attachment("ndns.phddays@gmail.com", "PhDdays 2016", " ", "  ",$mailtext, " ", $default_filetype='image/jpeg')) 
  }
} else {
  $deelnemer = array("lastname"    => "",
                     "firstname"   => "",
                     "supervisors" => "",
                     "gender"      => "",
                     "institute"   => "",
                     "research"     => "",
                     "office"    => "",
                     "address3"    => "",
                     "email"       => "",
                     "presentation"   => "",
                     "departday"   => "",
                     "hotelneeded" => null,
                     "moneyneeded" => null,
                     "status"      => "",
                     "sharing"     => null,
                     "vega"        => "",
                     "roommate"    => "",
                     "useragreement"    => "",
                     "zremarks"    => "");
  print_form($deelnemer, $dbc);
}

// ------------------ FUNCTIONS --------------- //


function check_input($deelnemer) {
  $errors = null;
  
  if(!isset($deelnemer["lastname"]) || ($deelnemer["lastname"] == "")) {
    $errors["lastname"] = "Please enter your last name.";
  }
  if(!isset($deelnemer["firstname"]) || ($deelnemer["firstname"] == "")) {
    $errors["firstname"] = "Please enter your first name.";
  }
  if(!isset($deelnemer["supervisors"]) || ($deelnemer["supervisors"] == "")) {
    $errors["supervisors"] = "Please enter your supervisors name.";
}

  if(!isset($deelnemer["useragreement"]) || ($deelnemer["useragreement"] == "") || ($deelnemer["useragreement"] == "d")  ) {
    $errors["useragreement"] = "Please select agree.";
  }

  if( (!isset($deelnemer["review"]) ||         ($deelnemer["review"] == "")) &&  (($deelnemer["presentation"] == "t") ||($deelnemer["presentation"] == "tp") )     ) {
    $errors["review"] = "Please enter if you want to get reviewed or not.";
  }

  if(!isset($deelnemer["gender"]) || ($deelnemer["gender"] == "")) {
    $errors["gender"] = "Please enter your gender.";
  }
  if(!isset($deelnemer["institute"]) || ($deelnemer["institute"] == "")) {
    $errors["institute"] = "Please enter your affialation.";
  }
  if(!isset($deelnemer["presentation"]) || ($deelnemer["presentation"] == "")) {
  $errors["presentation"] = "Please choose your form of presentation.";
  }
  if(!isset($deelnemer["research"]) || ($deelnemer["research"] == "")) {
  $errors["research"] = "Please write something about your research.";
  }
  if(!isset($deelnemer["office"]) || ($deelnemer["office"] == "")) {
    $errors["office"] = "Please specify the time of your office.";
  }
  if(!preg_match(EMAIL_REGEXP, $deelnemer["email"])) {
    $errors["email"] = "Please enter a valid email address.";
  }
  return $errors;
}

function get_post() {
  return array("lastname"    => $_POST["lastname"],
               "firstname"   => $_POST["firstname"],
               "supervisors" => $_POST["supervisors"],
               "gender"      => $_POST["gender"],
               "institute"   => $_POST["institute"],
               "research"    => $_POST["research"],
               "office"      => $_POST["office"],
               "email"       => $_POST["email"],
               "vega"        => $_POST["vega"],
               "sharing"     => $_POST["sharing"],
               "roommate"    => $_POST["roommate"],
               "presentation"=> $_POST["presentation"],
               "review"=> $_POST["review"],
               "useragreement"=> $_POST["useragreement"],
               "zremarks"    => $_POST["zremarks"]);
}


function print_form($deelnemer, $errors = null) {
  echo "<!-- Registration form -->\n\n"
      ."<form method='post' enctype='multipart/form-data' action='".$SERVER["PHP_SELF"]."'>\n"
      ."<p>Required fields are marked with *</p>\n"
      ."<table>\n"
      ."<tr><td><label for='lastname'>*Family name (last name)</label>:</td><td><input type='text' id='lastname' name='lastname' value='".$deelnemer["lastname"]."' maxlength=50 size=30> ".($errors["lastname"] ? "<br><span style='color: red'>".$errors["lastname"]."</span>" : "")."</td></tr>\n"
      ."<tr><td><label for='firstname'>*First name</label>:</td><td><input type='text' id='firstname' name='firstname' value='".$deelnemer["firstname"]."' maxlength=50 size=30> ".($errors["firstname"] ? "<br><span style='color: red'>".$errors["firstname"]."</span>" : "")."</td></tr>\n"

      ."<tr><td>*Gender:</td><td>\n"
      ."  <input type='radio' id='gender_m' name='gender' value='m' ".(($deelnemer["gender"] == "m") ? "checked='checked' " : "")."><label for='gender_m'>male</label><br>\n"
      ."  <input type='radio' id='gender_f' name='gender' value='f' ".(($deelnemer["gender"] == "f") ? "checked='checked' " : "")."><label for='gender_f'>female</label>".($errors["gender"] ? "<br><span style='color: red'>".$errors["gender"]."</span>" : "")."</td></tr>\n"

      ."<tr><td><label for='institute'>*Institute (affiliation)</label>:</td><td><input type='text' id='institute' name='institute' value='".$deelnemer["institute"]."' maxlength=50 size=30> ".($errors["institute"] ? "<br><span style='color: red'>".$errors["institute"]."</span>" : "")."</td></tr>\n"
	."<tr><td><label for='supervisors'>*Supervisors</label>:</td><td><input type='text' id='supervisors' name='supervisors' value='".$deelnemer["supervisors"]."' maxlength=100 size=30> ".($errors["supervisors"] ? "<br><span style='color: red'>".$errors["supervisors"]."</span>" : "")."</td></tr>\n"

	."<tr><td>*Years in office:</td><td>\n"
      ."  <input type='radio' id='year0' name='office' value='0' ".(($deelnemer["office"] == "0") ? "checked='checked' " : "")."><label for='year0'>not started yet</label><br>\n"
      ."  <input type='radio' id='year1' name='office' value='1' ".(($deelnemer["office"] == "1") ? "checked='checked' " : "")."><label for='year1'>first year</label><br>\n"
	."  <input type='radio' id='year2' name='office' value='2' ".(($deelnemer["office"] == "2") ? "checked='checked' " : "")."><label for='year2'>second year</label><br>\n"
	."  <input type='radio' id='year3' name='office' value='3' ".(($deelnemer["office"] == "3") ? "checked='checked' " : "")."><label for='year3'>third year</label><br>\n"
	."  <input type='radio' id='year4' name='office' value='4' ".(($deelnemer["office"] == "4") ? "checked='checked' " : "")."><label for='year4'>fourth year or longer</label>"
	.($errors["office"] ? "<br><span style='color: red'>".$errors["office"]."</span>" : "")."</td></tr>\n"

      ."<tr><td><label for='email'>*Email address </label>:</td><td><input type='text' id='email' name='email' value='".$deelnemer["email"]."' maxlength=60 size=30> ".($errors["email"] ? "<br><span style='color: red'>".$errors["email"]."</span>" : "")."</td></tr>\n"
    //hier

."<tr><td>*Form of presentation:</td><td>\n"
      ."  <input type='radio' id='talk' name='presentation' value='t' ".(($deelnemer["presentation"] == "t") ? "checked='checked' " : "")."><label for='talk'>Talk</label><br>\n"
      ."  <input type='radio' id='poster' name='presentation' value='p' ".(($deelnemer["presentation"] == "p") ? "checked='checked' " : "")."><label for='poster'>Poster</label><br>\n"
      ."  <input type='radio' id='both' name='presentation' value='tp' ".(($deelnemer["presentation"] == "tp") ? "checked='checked' " : "")."><label for='both'>Both</label><br>\n"
	.($errors["presentation"] ? "<br><span style='color: red'>".$errors["presentation"]."</span>" : "")."  <br>   </td></tr> \n"

      ."<tr><td>*I want to get peer-reviewed: <td>\n"
      ."  <input type='radio' id='review_y' name='review' value='y' ".(($deelnemer["review"] == "y") ? "checked='checked' " : "")."><label for='review_m'>yes</label><br>\n"
      ."  <input type='radio' id='review_n' name='review' value='n' ".(($deelnemer["review"] == "n") ? "checked='checked' " : "")."><label for='review_n'>no</label>".($errors["review"] ? "<br><span style='color: red'>".$errors["review"]."</span>" : "")."</td></tr>\n"
      ."<tr><td> <i><strong> What is this peer-reviewing you are talking about? </strong> <br>  
Like last year, you can get feedback on your talk. This will happen in small <br>
 feedback-rounds with each time a few participants reviewing your talk.  Since we    <br>
are not entirely sure if you want to get feedback on your talk we leave the choice to you.  <br> 
 <strong>Reviewing does not apply if you want to present a poster .</strong> </i > <br> <br> <td><td>\n"




      ."<ul style=''>\n"
      ."<tr><td>Are you a vegetarian, or do you have any dietary needs?:</td><td> <input type='text' name='vega' maxlength=50 size=30 value='".$deelnemer["vega"]."'>"."</td></tr>\n"
      ."<tr><td>Rooms are shared. If you have preference for a roommate, please say so:</td><td><input type='text' name='roommate' maxlength=50 size=30 value='".$deelnemer["roommate"]."'>".($errors["roommate"] ? "<br><span style='color: red'>".$errors["roommate"]."</span>" : "")."</li></td></tr>\n"
      ."</ul>"
      ."<tr>\n"
      ."<td>\n"
	."<p>*Some words (up to 75) about your research: <br> (This text will be used for the facebook)<br>\n"
      ."<textarea name='research' rows=5 cols=50>".$deelnemer["research"]."</textarea> ".($errors["research"] ? "<br><span style='color: red'>".$errors["research"]."</span>" : "")."</p>\n"
      ."</td>\n"
      ."<td>\n"
      ."<p>Additional comments:<br>\n"
      ."<textarea name='zremarks' rows=5 cols=50>".$deelnemer["zremarks"]."</textarea>".($errors["zremarks"] ? "<br><span style='color: red'>".$errors["zremarks"]."</span>" : "")."</p>\n"
      ."</td>\n"
	."</tr>\n"
	."</table>\n"
    .($errors["photo"] ? "<br><span style='color: red'>".$errors["photo"]."</span><br />" : "")
	."<label for='file'>Your photo for the facebook:</label> \n"
	."<input type='file' name='file' id='file'><br>\n"
	."<p>By not providing a photo you give us the freedom of choosing one for you.</p> \n"








   ."<tr><td>*<strong> PHD-REGISTRATION AGREEMENT: The cluster NDNS+ has been  <br>
so kind to sponsor this meeting, so that participation is free of charge.  <br>
It includes one night's stay, breakfast, lunches and a conference dinner.  <br>
However, if you register and do not attend the costs will not be funded and  <br>
you will be charged for the costs that we made for your participation. </strong> 
 </td><td>\n <br>"
      ."  <input type='radio' id='useragreement_a' name='useragreement' value='a' ".(($deelnemer["useragreement"] == "a") ? "checked='checked' " : "")."><label for='useragreement_a'>agree</label><br>\n"
      ."  <input type='radio' id='useragreement_d' name='useragreement' value='d' ".(($deelnemer["useragreement"] == "d") ? "checked='checked' " : "")."><label for='useragreement_d'>decline</label>".($errors["useragreement"] ? "<br><span style='color: red'>".$errors["useragreement"]."</span>" : "")."</td></tr>\n"



      ."<p><input type='submit' name='swi_submit' value='Submit'> <input type='reset' value='Clear'></p>\n"



  ."</form>\n"
  ."<!-- End of registration form --> \n\n";


}


function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
    $file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: image/jpeg; name=\"".$filename."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
    if (mail($mailto, $subject, "", $header)) {
      echo "<h2>Your registration was successful</h2>\n"
          ."<p>Thank you for registering for the <em>PhD days in dynamics and analysis 2016.</em><br>\n"
          ."<br></p>"
          ."<p>We look forward to seeing you there.</p>";
    } else {
      echo "<p>There was a problem processing your registration. Please inform <a href='mailto:ndns.phddays@gmail.com?subject=Problem with registration'>ndns.phddays@gmail.com</a>.</p>";
    }
}


/**
 * Retrieves the uploaded file location or fails with an exception
 * @return Full path to the uploaded file temporary location.
 */
function uploadfile(){
    if (isset($_FILES['file']) and $_FILES['file']['name'] != "") {        
        return $_FILES['file']['tmp_name'];
    }
    else {
	return "anonymous.jpg";
        //throw new Exception("Please provide a photo file");
    }
}

?>
