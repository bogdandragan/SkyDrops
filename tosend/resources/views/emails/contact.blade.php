<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Really Simple HTML Email Template</title>
<style>
/* -------------------------------------
    GLOBAL
------------------------------------- */
* {
  font-family: Arial, "Helvetica Neue", "Helvetica", Helvetica, sans-serif;
  font-size: 100%;
  line-height: 1.6em;
  margin: 0;
  padding: 0;
}

img {
  max-width: 600px;
  width: 100%;
}

body {
  -webkit-font-smoothing: antialiased;
  height: 100%;
  -webkit-text-size-adjust: none;
  width: 100% !important;
}


/* -------------------------------------
    ELEMENTS
------------------------------------- */
a {
  color: #1c84c6;
}

.btn-primary {
  Margin-bottom: 10px;
  width: auto !important;
}

.btn-primary td {
  background-color: #348eda; 
  border-radius: 25px;
  font-family: Arial, "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; 
  font-size: 14px; 
  text-align: center;
  vertical-align: top; 
}

.btn-primary td a {
  background-color: #348eda;
  border: solid 1px #348eda;
  border-radius: 25px;
  border-width: 10px 20px;
  display: inline-block;
  color: #ffffff;
  cursor: pointer;
  font-weight: bold;
  line-height: 2;
  text-decoration: none;
}

.last {
  margin-bottom: 0;
}

.first {
  margin-top: 0;
}

.padding {
  padding: 10px 0;
}


/* -------------------------------------
    BODY
------------------------------------- */
table.body-wrap {
  padding: 0 20px 20px 20px;
  width: 100%;
}

table.body-wrap .container {
  border: 1px solid #f0f0f0;
}


/* -------------------------------------
    FOOTER
------------------------------------- */
table.footer-wrap {
  clear: both !important;
  width: 100%;  
}

.footer-wrap .container p {
  color: #666666;
  font-size: 12px;
  
}

table.footer-wrap a {
  color: #1c84c6;
  text-decoration: none;
}

table.footer-wrap a:hover {
	text-decoration: underline;
}


/* -------------------------------------
    TYPOGRAPHY
------------------------------------- */
h1, 
h2, 
h3 {
  color: #111111;
  font-family: Arial, "Helvetica Neue", Helvetica, "Lucida Grande", sans-serif;
  font-weight: 200;
  line-height: 1.2em;
  margin: 40px 0 10px;
}

h1 {
  font-size: 26px;
  text-align: center;
  color: #414896;
  font-weight: 600;
  font-family: Arial, Tahoma;
  margin: 30px 0 0 0 ;
}
h2 {
  font-size: 28px;
}
h3 {
  font-size: 22px;
  margin-top: 15px;
  color: #414896;
  font-weight: 600;
}

p, 
ul, 
ol {
  font-size: 14px;
  font-weight: normal;
  margin-bottom: 10px;
}

ul li, 
ol li {
  margin-left: 5px;
  list-style-position: inside;
}

.logo {
	text-align: center;
	margin-top: 10px;
}

.logo img {
	width: auto;
}

/* ---------------------------------------------------
    RESPONSIVENESS
------------------------------------------------------ */

/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
.container {
  clear: both !important;
  display: block !important;
  Margin: 0 auto !important;
  max-width: 600px !important;
}

/* Set the padding on the td rather than the div for Outlook compatibility */
.body-wrap .container {
  padding: 20px;
}

/* This should also be a block element, so that it will fill 100% of the .container */
.content {
  display: block;
  margin: 0 auto;
  max-width: 600px;
}

/* Let's make sure tables in the content area are 100% wide */
.content table {
  width: 100%;
}

</style>
<style type="text/css"></style></head>

<body bgcolor="#f6f6f6">
<div class="logo">
	<img src="http://skydrops.skypro.ch/img/email-logo.png" />
</div>
<!-- body -->
<table class="body-wrap" bgcolor="#f6f6f6">
  <tbody><tr>
    <td></td>
    <td class="container" bgcolor="#FFFFFF">

      <!-- content -->
      <div class="content">
      <table>
        <tbody><tr>
          <td>
            <h3>Thank you for getting in touch!</h3>
			<p>We appreciate you contacting us about SKyDrops. We try to respond as soon as possible, so one of our Customer Service colleagues will get back to you within a few hours.</p>
			<p>Have a great day ahead!<br>Your <strong>SKyDrops-Team</strong></p>
          </td>
        </tr>
      </tbody></table>
      </div>
      <!-- /content -->
      
    </td>
    <td></td>
  </tr>
</tbody></table>
<!-- /body -->

<!-- footer -->
<table class="footer-wrap">
  <tbody><tr>
    <td></td>
    <td class="container">
      
      <!-- content -->
      <div class="content">
        <table>
          <tbody><tr>
            <td align="center">
              <p>Made by <a href="http://skydrops.skypro.ch/">SKyDrops</a>
              </p>
            </td>
          </tr>
        </tbody></table>
      </div>
      <!-- /content -->
      
    </td>
    <td></td>
  </tr>
</tbody></table>
<!-- /footer -->



</body></html>