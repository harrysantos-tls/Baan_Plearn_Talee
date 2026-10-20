<?php
/**
 * Template Name: Contact us (details first)
 *
 * Three contact cards, then the enquiry form. The form is your Zoho Forms form,
 * unchanged, so enquiries keep reaching Zoho. The dates arrive from the address
 * (?arrival=2026-12-20&departure=2026-12-27) and fill the form by themselves.
 *
 * @package bpt
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$tel = preg_replace( '/[^0-9+]/', '', bpt_detail( 'phone' ) );
$wa  = preg_replace( '/[^0-9]/', '', bpt_detail( 'whatsapp' ) );

while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/pagehero', null, array(
		'image'    => bpt_asset( 'home/sala.jpg' ),
		'title'    => __( 'Contact us', 'bpt' ),
		'crumb'    => __( 'Reservations', 'bpt' ),
		'size'     => 'short',
		'position' => '50% 58%',
	) );
endwhile;
?>
<section class="block">
	<div class="wrap">
		<div class="ccards">
			<a class="ccard" href="mailto:<?php echo esc_attr( bpt_detail( 'email' ) ); ?>">
				<div class="kicker"><?php esc_html_e( 'Email', 'bpt' ); ?></div>
				<div class="ccard__v"><?php echo esc_html( bpt_detail( 'email' ) ); ?></div>
			</a>
			<a class="ccard" href="tel:<?php echo esc_attr( $tel ); ?>">
				<div class="kicker"><?php esc_html_e( 'Phone and WhatsApp', 'bpt' ); ?></div>
				<div class="ccard__v"><?php echo esc_html( bpt_detail( 'phone' ) ); ?></div>
			</a>
			<a class="ccard" href="<?php echo esc_url( bpt_page_url( 'location' ) ); ?>">
				<div class="kicker"><?php esc_html_e( 'Visit', 'bpt' ); ?></div>
				<div class="ccard__v"><?php echo esc_html( bpt_detail( 'address' ) ); ?></div>
			</a>
		</div>

		<div class="cform">
			<h2 class="cform__t"><?php esc_html_e( 'Tell us about your stay', 'bpt' ); ?></h2>
			<p class="cform__sub"><?php esc_html_e( 'Send us your dates and a few details and our reservations team will come back with availability, rates, and anything else you need to decide.', 'bpt' ); ?></p>

<!-- ZOHO Start  -->
<style>
	.field input:disabled {
		background-color: transparent;
	}
	.field select {
		padding-left: 5px;
	}
</style>
<script>
    jQuery(document).ready(function () {
        function formatForForm(dateStr) {
            if (!dateStr) return "";
            const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            const parts = dateStr.split('-');
            if (parts.length !== 3) return dateStr;

            const year = parts[0];
            const month = months[parseInt(parts[1], 10) - 1];
            const day = parts[2];

            return day + "-" + month + "-" + year;
        }

        // 2. The Injection Logic
        function injectData() {
            const urlParams = new URLSearchParams(window.location.search);
            const arrival = urlParams.get('arrival');
            const departure = urlParams.get('departure');

            if (arrival) {
                jQuery('#arrival').val(formatForForm(arrival));
            }
            if (departure) {
                jQuery('#departure').val(formatForForm(departure));
            }
        }
        function toggleAgeOfChildren() {
            if (jQuery('#childrenDropdown').val() === "" || jQuery('#childrenDropdown').val() === "0") {
                jQuery('#ageOfChildren').prop('disabled', true);
                jQuery('#ageOfChildren').val('N/A');
            } else {
                jQuery('#ageOfChildren').prop('disabled', false);
                jQuery('#ageOfChildren').val('');
            }
        }

        jQuery('#childrenDropdown').change(toggleAgeOfChildren);

        // Initial check
        toggleAgeOfChildren();
        setTimeout(injectData, 500);
    });
</script>
<script>
    jQuery(function () {
        jQuery('#arrival').datepicker({
            dateFormat: "dd-M-yy",
            minDate: 0,
            onSelect: function (date) {
                var date2 = jQuery('#arrival').datepicker('getDate');
                date2.setDate(date2.getDate() + 2);
                jQuery('#departure').datepicker('setDate', date2);
                jQuery('#departure').datepicker('option', 'minDate', date2);
            }
        }).attr('type', 'text');

        jQuery('#departure').datepicker({
            minDate: "+2",
            dateFormat: "dd-M-yy",
            onClose: function () {
                var dt1 = jQuery('#arrival').datepicker('getDate');
                console.log(dt1);
                var dt2 = jQuery('#departure').datepicker('getDate');
                if (dt2 <= dt1) {
                    var minDate = jQuery('#departure').datepicker('option', 'minDate');
                    jQuery('#departure').datepicker('setDate', minDate);
                }
            }
        }).attr('type', 'text');
        jQuery('#arrival2').datepicker({
            dateFormat: "dd-M-yy",
            minDate: 0,
            onSelect: function (date) {
                var date2 = jQuery('#arrival2').datepicker('getDate');
                date2.setDate(date2.getDate() + 2);
                jQuery('#departure2').datepicker('setDate', date2);
                jQuery('#departure2').datepicker('option', 'minDate', date2);
            }
        }).attr('type', 'text');

        jQuery('#departure2').datepicker({
            minDate: "+2",
            dateFormat: "dd-M-yy",
            onClose: function () {
                var dt1 = jQuery('#arrival2').datepicker('getDate');
                console.log(dt1);
                var dt2 = jQuery('#departure2').datepicker('getDate');
                if (dt2 <= dt1) {
                    var minDate = jQuery('#departure2').datepicker('option', 'minDate');
                    jQuery('#departure2').datepicker('setDate', minDate);
                }
            }
        }).attr('type', 'text');

        /*Mobile Display*/
        jQuery('#marrival').datepicker({
            dateFormat: "dd-M-yy",
            minDate: 0,
            onSelect: function (date) {
                var date2 = jQuery('#marrival').datepicker('getDate');
                date2.setDate(date2.getDate() + 2);
                jQuery('#mdeparture').datepicker('setDate', date2);
                jQuery('#mdeparture').datepicker('option', 'minDate', date2);
            }
        }).attr('type', 'text');

        jQuery('#mdeparture').datepicker({
            minDate: "+2",
            dateFormat: "dd-M-yy",
            onClose: function () {
                var dt1 = jQuery('#marrival').datepicker('getDate');
                console.log(dt1);
                var dt2 = jQuery('#mdeparture').datepicker('getDate');
                if (dt2 <= dt1) {
                    var minDate = jQuery('#mdeparture').datepicker('option', 'minDate');
                    jQuery('#mdeparture').datepicker('setDate', minDate);
                }
            }
        }).attr('type', 'text');
        /*End Mobile Display*/

        jQuery('#in-arrival').datepicker({
            dateFormat: "dd-M-yy",
            minDate: 0,
            onSelect: function (date) {
                var date2 = jQuery('#in-arrival').datepicker('getDate');
                date2.setDate(date2.getDate() + 2);
                jQuery('#in-departure').datepicker('setDate', date2);
                jQuery('#in-departure').datepicker('option', 'minDate', date2);
            }
        }).attr('type', 'text');
        jQuery('#in-departure').datepicker({
            minDate: "+2",
            dateFormat: "dd-M-yy",
            onClose: function () {
                var dt1 = jQuery('#in-arrival').datepicker('getDate');
                console.log(dt1);
                var dt2 = jQuery('#in-departure').datepicker('getDate');
                if (dt2 <= dt1) {
                    var minDate = jQuery('#in-departure').datepicker('option', 'minDate');
                    jQuery('#in-departure').datepicker('setDate', minDate);
                }
            }
        }).attr('type', 'text');

    });
</script>
<style>
    input:disabled {
        background-color: #e6e6e6;
    }
</style>

<!-- Change or deletion of the name attributes in the input tag will lead to empty values on record submission-->
<form
    action='https://forms.zohopublic.com/default457/form/BaanPlearnTaleeBookingForm/formperma/aCYgRndPEFqlPuw3dilANd0lomFV9xg3TD_QJzEEmz0/htmlRecords/submit'
    class="form-grid" name='form' id='form' method='POST' accept-charset='UTF-8' enctype='multipart/form-data'>
    <input type="hidden" name="zf_referrer_name"
        value=""><!-- To Track referrals , place the referrer name within the " " in the above hidden input field -->
    <input type="hidden" name="zf_redirect_url"
        value="https://baanplearntalee.com/thank-you/"><!-- To redirect to a specific page after record submission , place the respective url within the " " in the above hidden input field -->
    <input type="hidden" name="zc_gad"
        value=""><!-- If GCLID is enabled in Zoho CRM Integration, click details of AdWords Ads will be pushed to Zoho CRM -->


    <!--Single Line-->
    <div class="field">
        <label> First Name *</label>
        <input type="text" name="SingleLine1" value="" fieldType=1 maxlength="255" placeholder=""
            class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required fname" required />
    </div>

    <!--Single Line-->
    <div class="field">
        <label> Last Name *</label>
        <input type="text" name="SingleLine" value="" fieldType=1 maxlength="255" placeholder=""
            class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required lname" required />
    </div>

    <div class="clear"></div>
    <div class="space-height-featured"></div>

    <!--Email-->
    <div class="field">
        <label> Email *</label>
        <input type="text" maxlength="255" name="Email" value="" fieldType=9 placeholder=""
            class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email email"
            required />
    </div>

    <!--Phone-->
    <div class="field">
        <label> Phone Number</label>
        <input type="text" compname="PhoneNumber" name="PhoneNumber_countrycode" phoneFormat="1"
            isCountryCodeEnabled=false maxlength="20" value="" fieldType=11 id="international_PhoneNumber_countrycode"
            placeholder=""
            class="wpcf7-form-control wpcf7-text wpcf7-tel wpcf7-validates-as-required wpcf7-validates-as-tel contact-number"
            required />
    </div>

    <div class="clear"></div>
    <div class="space-height-featured"></div>

    <!--Date-->
    <div class="field">
        <label> Date of Arrival </label>
        <input type="text" name="Date" value="" maxlength="25" placeholder="dd-MMM-yyyy" id="arrival"
            class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required hasdatepicker" required />
    </div>

    <!--Date-->
    <div class="field">
        <label> Date of Departure </label>
        <input type="text" name="Date1" value="" maxlength="25" placeholder="dd-MMM-yyyy" id="departure"
            class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required hasdatepicker" required />
    </div>

    <div class="clear"></div>
    <div class="space-height-featured"></div>


    <!--Dropdown-->
    <div class="field">
        <label>Flexibility of Dates</label>
        <select name="Dropdown1"
            class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required et_pb_contact_select input" required>
            <option selected="true" value="">-Select-</option>
            <option value="Yes, my dates are flexible by 1 - 2 days">Yes, my dates are flexible by 1 - 2 days</option>
            <option value="Yes, my dates are flexible by up to a week">Yes, my dates are flexible by up to a week
            </option>
            <option value="No, my dates are fixed">No, my dates are fixed</option>
        </select>
    </div>

    <!--Dropdown-->
    <div class="field">
        <label>Country of Residence</label>
        <select name="Dropdown"
            class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required et_pb_contact_select input" required>
            <option selected="true" value="">-Select-</option>
            <option value="Afghanistan">Afghanistan</option>
            <option value="Akrotiri">Akrotiri</option>
            <option value="Albania">Albania</option>
            <option value="Algeria">Algeria</option>
            <option value="American Samoa">American Samoa</option>
            <option value="Andorra">Andorra</option>
            <option value="Angola">Angola</option>
            <option value="Anguilla">Anguilla</option>
            <option value="Antarctica">Antarctica</option>
            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
            <option value="Argentina">Argentina</option>
            <option value="Armenia">Armenia</option>
            <option value="Aruba">Aruba</option>
            <option value="Ashmore and Cartier Islands">Ashmore and Cartier Islands</option>
            <option value="Australia">Australia</option>
            <option value="Austria">Austria</option>
            <option value="Azerbaijan">Azerbaijan</option>
            <option value="Bahrain">Bahrain</option>
            <option value="Bangladesh">Bangladesh</option>
            <option value="Barbados">Barbados</option>
            <option value="Bassas Da India">Bassas Da India</option>
            <option value="Belarus">Belarus</option>
            <option value="Belgium">Belgium</option>
            <option value="Belize">Belize</option>
            <option value="Benin">Benin</option>
            <option value="Bermuda">Bermuda</option>
            <option value="Bhutan">Bhutan</option>
            <option value="Bolivia">Bolivia</option>
            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
            <option value="Botswana">Botswana</option>
            <option value="Bouvet Island">Bouvet Island</option>
            <option value="Brazil">Brazil</option>
            <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
            <option value="British Virgin Islands">British Virgin Islands</option>
            <option value="Brunei">Brunei</option>
            <option value="Bulgaria">Bulgaria</option>
            <option value="Burkina Faso">Burkina Faso</option>
            <option value="Burma">Burma</option>
            <option value="Burundi">Burundi</option>
            <option value="Cambodia">Cambodia</option>
            <option value="Cameroon">Cameroon</option>
            <option value="Canada">Canada</option>
            <option value="Cape Verde">Cape Verde</option>
            <option value="Caribbean Netherlands">Caribbean Netherlands</option>
            <option value="Cayman Islands">Cayman Islands</option>
            <option value="Central African Republic">Central African Republic</option>
            <option value="Chad">Chad</option>
            <option value="Chile">Chile</option>
            <option value="China">China</option>
            <option value="Christmas Island">Christmas Island</option>
            <option value="Clipperton Island">Clipperton Island</option>
            <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
            <option value="Colombia">Colombia</option>
            <option value="Comoros">Comoros</option>
            <option value="Cook Islands">Cook Islands</option>
            <option value="Coral Sea Islands">Coral Sea Islands</option>
            <option value="Costa Rica">Costa Rica</option>
            <option value="Cote D'Ivoire">Cote D'Ivoire</option>
            <option value="Croatia">Croatia</option>
            <option value="Cuba">Cuba</option>
            <option value="Curaçao">Curaçao</option>
            <option value="Cyprus">Cyprus</option>
            <option value="Czech Republic">Czech Republic</option>
            <option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
            <option value="Denmark">Denmark</option>
            <option value="Dhekelia">Dhekelia</option>
            <option value="Djibouti">Djibouti</option>
            <option value="Dominica">Dominica</option>
            <option value="Dominican Republic">Dominican Republic</option>
            <option value="Ecuador">Ecuador</option>
            <option value="Egypt">Egypt</option>
            <option value="El Salvador">El Salvador</option>
            <option value="Equatorial Guinea">Equatorial Guinea</option>
            <option value="Eritrea">Eritrea</option>
            <option value="Estonia">Estonia</option>
            <option value="Ethiopia">Ethiopia</option>
            <option value="Europa Island">Europa Island</option>
            <option value="Falkland Islands (Islas Malvinas)">Falkland Islands (Islas Malvinas)</option>
            <option value="Faroe Islands">Faroe Islands</option>
            <option value="Federated States of Micronesia">Federated States of Micronesia</option>
            <option value="Fiji">Fiji</option>
            <option value="Finland">Finland</option>
            <option value="France">France</option>
            <option value="French Guiana">French Guiana</option>
            <option value="French Polynesia">French Polynesia</option>
            <option value="French Southern and Antarctic Lands">French Southern and Antarctic Lands</option>
            <option value="Gabon">Gabon</option>
            <option value="Gaza Strip">Gaza Strip</option>
            <option value="Georgia">Georgia</option>
            <option value="Germany">Germany</option>
            <option value="Ghana">Ghana</option>
            <option value="Gibraltar">Gibraltar</option>
            <option value="Glorioso Islands">Glorioso Islands</option>
            <option value="Greece">Greece</option>
            <option value="Greenland">Greenland</option>
            <option value="Grenada">Grenada</option>
            <option value="Guadeloupe">Guadeloupe</option>
            <option value="Guam">Guam</option>
            <option value="Guatemala">Guatemala</option>
            <option value="Guernsey">Guernsey</option>
            <option value="Guinea">Guinea</option>
            <option value="Guinea-bissau">Guinea-bissau</option>
            <option value="Guyana">Guyana</option>
            <option value="Haiti">Haiti</option>
            <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
            <option value="Holy See (Vatican City)">Holy See (Vatican City)</option>
            <option value="Honduras">Honduras</option>
            <option value="Hong Kong">Hong Kong</option>
            <option value="Hungary">Hungary</option>
            <option value="Iceland">Iceland</option>
            <option value="India">India</option>
            <option value="Indonesia">Indonesia</option>
            <option value="Iran">Iran</option>
            <option value="Iraq">Iraq</option>
            <option value="Ireland">Ireland</option>
            <option value="Isle of Man">Isle of Man</option>
            <option value="Israel">Israel</option>
            <option value="Italy">Italy</option>
            <option value="Jamaica">Jamaica</option>
            <option value="Jan Mayen">Jan Mayen</option>
            <option value="Japan">Japan</option>
            <option value="Jersey">Jersey</option>
            <option value="Jordan">Jordan</option>
            <option value="Juan De Nova Island">Juan De Nova Island</option>
            <option value="Kazakhstan">Kazakhstan</option>
            <option value="Kenya">Kenya</option>
            <option value="Kiribati">Kiribati</option>
            <option value="Kosovo">Kosovo</option>
            <option value="Kuwait">Kuwait</option>
            <option value="Kyrgyzstan">Kyrgyzstan</option>
            <option value="Laos">Laos</option>
            <option value="Latvia">Latvia</option>
            <option value="Lebanon">Lebanon</option>
            <option value="Lesotho">Lesotho</option>
            <option value="Liberia">Liberia</option>
            <option value="Libya">Libya</option>
            <option value="Liechtenstein">Liechtenstein</option>
            <option value="Lithuania">Lithuania</option>
            <option value="Luxembourg">Luxembourg</option>
            <option value="Macau">Macau</option>
            <option value="Macedonia">Macedonia</option>
            <option value="Madagascar">Madagascar</option>
            <option value="Malawi">Malawi</option>
            <option value="Malaysia">Malaysia</option>
            <option value="Maldives">Maldives</option>
            <option value="Mali">Mali</option>
            <option value="Malta">Malta</option>
            <option value="Marshall Islands">Marshall Islands</option>
            <option value="Martinique">Martinique</option>
            <option value="Mauritania">Mauritania</option>
            <option value="Mauritius">Mauritius</option>
            <option value="Mayotte">Mayotte</option>
            <option value="Mexico">Mexico</option>
            <option value="Moldova">Moldova</option>
            <option value="Monaco">Monaco</option>
            <option value="Mongolia">Mongolia</option>
            <option value="Montenegro">Montenegro</option>
            <option value="Montserrat">Montserrat</option>
            <option value="Morocco">Morocco</option>
            <option value="Mozambique">Mozambique</option>
            <option value="Myanmar">Myanmar</option>
            <option value="Namibia">Namibia</option>
            <option value="Nauru">Nauru</option>
            <option value="Navassa Island">Navassa Island</option>
            <option value="Nepal">Nepal</option>
            <option value="Netherlands">Netherlands</option>
            <option value="Netherlands Antilles">Netherlands Antilles</option>
            <option value="New Caledonia">New Caledonia</option>
            <option value="New Zealand">New Zealand</option>
            <option value="Nicaragua">Nicaragua</option>
            <option value="Niger">Niger</option>
            <option value="Nigeria">Nigeria</option>
            <option value="Niue">Niue</option>
            <option value="Norfolk Island">Norfolk Island</option>
            <option value="North Korea">North Korea</option>
            <option value="Northern Mariana Islands">Northern Mariana Islands</option>
            <option value="Norway">Norway</option>
            <option value="Oman">Oman</option>
            <option value="Pakistan">Pakistan</option>
            <option value="Palau">Palau</option>
            <option value="Palestine">Palestine</option>
            <option value="Panama">Panama</option>
            <option value="Papua New Guinea">Papua New Guinea</option>
            <option value="Paracel Islands">Paracel Islands</option>
            <option value="Paraguay">Paraguay</option>
            <option value="Peru">Peru</option>
            <option value="Philippines">Philippines</option>
            <option value="Pitcairn Islands">Pitcairn Islands</option>
            <option value="Poland">Poland</option>
            <option value="Portugal">Portugal</option>
            <option value="Puerto Rico">Puerto Rico</option>
            <option value="Qatar">Qatar</option>
            <option value="Republic of the Congo">Republic of the Congo</option>
            <option value="Reunion">Reunion</option>
            <option value="Romania">Romania</option>
            <option value="Russia">Russia</option>
            <option value="Rwanda">Rwanda</option>
            <option value="Saint BarthÃ©lemy">Saint BarthÃ©lemy</option>
            <option value="Saint Helena">Saint Helena</option>
            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
            <option value="Saint Lucia">Saint Lucia</option>
            <option value="Saint Martin">Saint Martin</option>
            <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
            <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
            <option value="Samoa">Samoa</option>
            <option value="San Marino">San Marino</option>
            <option value="Sao Tome and Principe">Sao Tome and Principe</option>
            <option value="Saudi Arabia">Saudi Arabia</option>
            <option value="Senegal">Senegal</option>
            <option value="Serbia">Serbia</option>
            <option value="Seychelles">Seychelles</option>
            <option value="Sierra Leone">Sierra Leone</option>
            <option value="Singapore">Singapore</option>
            <option value="Sint Maarten">Sint Maarten</option>
            <option value="Slovakia">Slovakia</option>
            <option value="Slovenia">Slovenia</option>
            <option value="Solomon Islands">Solomon Islands</option>
            <option value="Somalia">Somalia</option>
            <option value="South Africa">South Africa</option>
            <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands
            </option>
            <option value="South Korea">South Korea</option>
            <option value="South Sudan">South Sudan</option>
            <option value="Spain">Spain</option>
            <option value="Spratly Islands">Spratly Islands</option>
            <option value="Sri Lanka">Sri Lanka</option>
            <option value="Sudan">Sudan</option>
            <option value="Suriname">Suriname</option>
            <option value="Svalbard">Svalbard</option>
            <option value="Swaziland">Swaziland</option>
            <option value="Sweden">Sweden</option>
            <option value="Switzerland">Switzerland</option>
            <option value="Syria">Syria</option>
            <option value="Taiwan">Taiwan</option>
            <option value="Tajikistan">Tajikistan</option>
            <option value="Tanzania">Tanzania</option>
            <option value="Thailand">Thailand</option>
            <option value="The Bahamas">The Bahamas</option>
            <option value="The Gambia">The Gambia</option>
            <option value="Timor-leste">Timor-leste</option>
            <option value="Togo">Togo</option>
            <option value="Tokelau">Tokelau</option>
            <option value="Tonga">Tonga</option>
            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
            <option value="Tromelin Island">Tromelin Island</option>
            <option value="Tunisia">Tunisia</option>
            <option value="Turkey">Turkey</option>
            <option value="Turkmenistan">Turkmenistan</option>
            <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
            <option value="Tuvalu">Tuvalu</option>
            <option value="Uganda">Uganda</option>
            <option value="Ukraine">Ukraine</option>
            <option value="United Arab Emirates">United Arab Emirates</option>
            <option value="United Kingdom">United Kingdom</option>
            <option value="United States">United States</option>
            <option value="Uruguay">Uruguay</option>
            <option value="Uzbekistan">Uzbekistan</option>
            <option value="Vanuatu">Vanuatu</option>
            <option value="Venezuela">Venezuela</option>
            <option value="Vietnam">Vietnam</option>
            <option value="Virgin Islands">Virgin Islands</option>
            <option value="Wake Island">Wake Island</option>
            <option value="Wallis and Futuna">Wallis and Futuna</option>
            <option value="West Bank">West Bank</option>
            <option value="Western Sahara">Western Sahara</option>
            <option value="Yemen">Yemen</option>
            <option value="Zambia">Zambia</option>
            <option value="Zimbabwe">Zimbabwe</option>
        </select>
    </div>

    <div class="clear"></div>
    <div class="space-height-featured"></div>

    <!--Dropdown-->
    <div class="field">
        <label>Number of Adults</label>
        <select name="Dropdown2"
            class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required et_pb_contact_select input" required>
            <option selected="true" value="">-Select-</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
        </select>
    </div>

    <!--Dropdown-->
    <div class="field">
        <label>Number of Children</label>
        <select name="Dropdown3" id='childrenDropdown'
            class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required et_pb_contact_select input" required>
            <option selected="true" value="">-Select-</option>
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="More than 10">More than 10</option>
        </select>
    </div>

    <div class="clear"></div>
    <div class="space-height-featured"></div>

    <div class="field">
        <label>Age of Children</label>
        <input type="text" id="ageOfChildren" name="SingleLine8" value="" fieldtype="1" maxlength="255" placeholder="For multiple children, please list all ages. For example: 3, 5, 10."
            disabled="">
    </div>

    <!--Dropdown-->
    <div class="field">
        <label>Required Number of Bedrooms</label>
        <select name="Dropdown4"
            class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required et_pb_contact_select input bedrooms"
            required>
            <option selected="true" value="">-Select-</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
        </select>
    </div>

    <div class="clear"></div>
    <div class="space-height-featured"></div>

    <!--Single Line-->
    <div class="field">
        <label> Budget Per Night (in USD)</label>
        <select name="SingleLine2"
            class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required et_pb_contact_select input" required>
            <option selected="true" value="">-Select-</option>
            <option>$1,000 - $2,000</option>
            <option>$2,000 - $3,000</option>
            <option>$3,000 - $4,000</option>
            <option>$4,000 and above</option>
            <option>Flexible</option>
        </select>
    </div>

    <!--Single Line-->
    <div class="field">
        <label> Purpose of Stay </label>
        <select name="SingleLine3"
            class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required et_pb_contact_select input" required>
            <option selected="true" value="">-Select-</option>
            <option>General holiday</option>
            <option>Vacation with family</option>
            <option>Vacation with friends</option>
            <option>Birthday celebration</option>
            <option>Anniversary celebration</option>
            <option>Work or Corporate Retreat</option>
            <option>Reunion</option>
            <option>Wedding/Event</option>
            <option>Other</option>
        </select>
    </div>

    <div class="clear"></div>
    <div class="space-height-featured"></div>
    <div class="field">
        <label> Preferred Communication Channel * </label>
        <select name="Dropdown5"
            class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required et_pb_contact_select input" required="">
            <option selected="true" value="">-Select-</option>
            <option value="Email">Email</option>
            <option value="WhatsApp/Chat">WhatsApp/Chat</option>
            <option value="Phone Call">Phone Call</option>
            <option value="Email and WhatsApp">Email and WhatsApp</option>
        </select>
    </div>
    <!--Single Line-->
    <div class="field">
        <label> Where did you hear about us? </label>
        <select name="SingleLine4"
            class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required et_pb_contact_select input" required>
            <option selected="true" value="">-Select-</option>
            <option>Google search</option>
            <option>Facebook</option>
            <option>Instagram</option>
            <option>YouTube</option>
            <option>Other social media</option>
            <option>Recommended by friend/family</option>
            <option>Return guest</option>
            <option>Online article</option>
            <option>Newsletter</option>
            <option>Agent</option>
            <option>AI Tools (ChatGPT, Gemini, etc.)</option>
            <option>Other</option>
        </select>
    </div>

    <div class="clear"></div>
    <div class="space-height-featured"></div>

    <!--Multi Line-->
    <div class="field field--full">
        <label> Comments </label>
        <textarea name="MultiLine" maxlength="1500" placeholder="" class="wpcf7-form-control wpcf7-textarea comments"
            cols="40" rows="5"></textarea>
    </div>

    <div class="field field--full">
		<label class="consent">
			<input type="checkbox" id="DecisionBox" name="DecisionBox">
			<span>I agree to receive exclusive
				offers, newsletters, and WhatsApp updates from TLS Travel Group Ltd.. I understand I can unsubscribe at any
				time.
			</span>					
		</label>
    </div>
    <div style="display: none;">
        <!--Single Line-->
        <label> Type of Contact *</label>
        <input type="text" name="SingleLine5" value="Signature Guest" fieldType=1 maxlength="255" placeholder="" />

        <!--Website-->
        <label> URL </label>
        <input type="text" maxlength="2083" name="Website" value="https://baanplearntalee.com/contact-us/" placeholder="" id="website" />

        <!--Single Line-->
        <label> Property / Page Title </label>
        <input type="text" name="SingleLine6" value="Baan Plearn Talee" fieldType=1 maxlength="255" placeholder="" id="title" />

        <!--Single Line-->
        <label>Destination</label>
        <input type="text" name="SingleLine7" value="Phuket, Thailand" fieldType=1 maxlength="255" placeholder="" />
    </div>
	<div class="field field--full">
		<button type="submit" class="btn btn-submit" id="btn-submit">SUBMIT</button>		
	</div>
</form>

<!-- ZOHO End  -->

		</div>
	</div>
</section>
<?php get_footer(); ?>
