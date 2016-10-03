<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Payments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function(Blueprint $table) {
        	$table->increments('id', 11);
        	$table->string('change_type', 20)->nullable();
        	$table->string('covered_recipient_type', 50)->nullable();
        	$table->string('teaching_hospital_ccn', 6)->nullable();
        	$table->decimal('teaching_hospital_id', 10, 0)->nullable();
        	$table->string('teaching_hospital_name', 100)->nullable();
        	$table->decimal('physician_profile_id', 10, 0)->nullable();
        	$table->string('physician_first_name', 20)->nullable();
        	$table->string('physician_middle_name', 20)->nullable();
        	$table->string('physician_last_name', 35)->nullable();
        	$table->string('physician_name_suffix', 5)->nullable();
        	$table->string('recipient_primary_business_street_address_line1', 55)->nullable();
        	$table->string('recipient_primary_business_street_address_line2', 55)->nullable();
        	$table->string('recipient_city', 40)->nullable();
        	$table->char('recipient_state', 2)->nullable();
        	$table->string('recipient_zip_code', 10)->nullable();
        	$table->string('recipient_country', 100)->nullable();
        	$table->string('recipient_province', 20)->nullable();
        	$table->string('recipient_postal_code', 255)->nullable();
        	$table->string('physician_primary_type', 100)->nullable();
        	$table->string('physician_specialty', 300)->nullable();
        	$table->char('physician_license_state_code1', 2)->nullable();
        	$table->char('physician_license_state_code2', 2)->nullable();
        	$table->char('physician_license_state_code3', 2)->nullable();
        	$table->char('physician_license_state_code4', 2)->nullable();
        	$table->char('physician_license_state_code5', 2)->nullable();
        	$table->string('submitting_applicable_manufacturer_or_applicable_gpo_name', 100)->nullable();
        	$table->string('applicable_manufacturer_or_applicable_gpo_making_payment_id', 38)->nullable();
        	$table->string('applicable_manufacturer_or_applicable_gpo_making_payment_name', 100)->nullable();
        	$table->char('applicable_manufacturer_or_applicable_gpo_making_payment_state', 2)->nullable();
        	$table->string('applicable_manufacturer_or_applicable_gpo_making_payment_country', 100)->nullable();
        	$table->decimal('total_amount_of_payment_usdollars', 15, 2)->nullable();
        	$table->date('date_of_payment')->nullable();
        	$table->integer('number_of_payments_included_in_total_amount')->length(11)->nullable()->unsigened();
        	$table->string('form_of_payment_or_transfer_of_value', 100)->nullable();
        	$table->string('nature_of_payment_or_transfer_of_value', 200)->nullable();
        	$table->string('city_of_travel', 40)->nullable();
        	$table->char('state_of_travel', 2)->nullable();
        	$table->string('country_of_travel', 100)->nullable();
        	$table->char('physician_ownership_indicator', 3)->nullable();
        	$table->string('third_party_payment_recipient_indicator', 50)->nullable();
        	$table->string('name_third_party_entity_receiving_payment_or_transfer_of_value', 50)->nullable();
        	$table->char('charity_indicator', 3)->nullable();
        	$table->char('third_party_equals_covered_recipient_indicator', 3)->nullable();
        	$table->string('contextual_information', 500)->nullable();
        	$table->char('delay_in_publication_indicator', 3)->nullable();
        	$table->unsignedInteger('record_id')->length(11)->nullable();
        	$table->char('dispute_status_for_publication', 3)->nullable();
        	$table->string('product_indicator', 50)->nullable();
        	$table->string('name_of_associated_covered_drug_or_biological1', 100)->nullable();
        	$table->string('name_of_associated_covered_drug_or_biological2', 100)->nullable();
        	$table->string('name_of_associated_covered_drug_or_biological3', 100)->nullable();
        	$table->string('name_of_associated_covered_drug_or_biological4', 100)->nullable();
        	$table->string('name_of_associated_covered_drug_or_biological5', 100)->nullable();
        	$table->string('ndc_of_associated_covered_drug_or_biological1', 12)->nullable();
        	$table->string('ndc_of_associated_covered_drug_or_biological2', 12)->nullable();
        	$table->string('ndc_of_associated_covered_drug_or_biological3', 12)->nullable();
        	$table->string('ndc_of_associated_covered_drug_or_biological4', 12)->nullable();
        	$table->string('ndc_of_associated_covered_drug_or_biological5', 12)->nullable();
        	$table->string('name_of_associated_covered_device_or_medical_supply1', 100)->nullable();
        	$table->string('name_of_associated_covered_device_or_medical_supply2', 100)->nullable();
        	$table->string('name_of_associated_covered_device_or_medical_supply3', 100)->nullable();
        	$table->string('name_of_associated_covered_device_or_medical_supply4', 100)->nullable();
        	$table->string('name_of_associated_covered_device_or_medical_supply5', 100)->nullable();
        	$table->char('program_year', 4)->nullable();
        	$table->date('payment_publication_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payments');
    }
}
