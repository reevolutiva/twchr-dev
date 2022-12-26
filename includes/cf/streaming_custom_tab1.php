<div class="twchr_car_tab1">
	<div class="twchr-card-row is_recurring">
		<label><?php twchr_esc_i18n( 'Is Recurring ?', 'html' ); ?></label>
		<div class="is-recurring-input-group">
			<div>
				<input id="twchr_schedule_card_input--is_recurrig__yes"  name="twchr_schedule_card_input--is_recurrig" class="twchr_schedule_card_input" type="radio" value="true" <?php echo $is_recurring == true ? 'checked' : '' ?> >
				<label for="twchr_schedule_card_input--is_recurrig__yes"><?php twchr_esc_i18n( 'Yes', 'html' ); ?></label>
			</div>
			<div>
				<input id="twchr_schedule_card_input--is_recurri__no"  name="twchr_schedule_card_input--is_recurrig" class="twchr_schedule_card_input" type="radio" value="false" <?php echo $is_recurring == false ? 'checked' : '' ?>>
				<label for="twchr_schedule_card_input--is_recurrig__no"><?php twchr_esc_i18n( 'No', 'html' ); ?></label>
			</div>
		</div>
		<p><?php twchr_esc_i18n("¿Is this streaming part of a serie?",'html');?></p>
	</div>
	<div class="twchr-card-row">
		<label for="twchr_schedule_card_input--title"><?php twchr_esc_i18n( 'Streaming Title', 'html' ); ?></label>
		<input id="twchr_schedule_card_input--title" name="twchr_schedule_card_input--title" class="twchr_schedule_card_input" type="text" <?php echo $is_recurring == true ? 'disabled="true"' : ''; ?>  value="<?php echo $title; ?>">
		<label for="twchr_schedule_card_input--category"><?php twchr_esc_i18n( 'Twitch category', 'html' ); ?></label>
		<div class="twchr_cards_input_badges">
			<input id="twchr_schedule_card_input--category" class="twchr_schedule_card_input" name="twchr_schedule_card_input--category__name" type="text" value="<?php echo ! empty( $term_cat_twcht_name ) ? $term_cat_twcht_name : ''; ?>" />
			<badges><?php echo $term_cat_twcht_list; ?></badges>
		</div>
	</div>
	<div class="twchr-card-row">
		
		<label for="twchr_schedule_card_input--dateTime"><?php twchr_esc_i18n( 'Streaming Date & Time', 'html' ); ?></label>
		<div>
			<input id="twchr_schedule_card_input--dateTime"  name="twchr_schedule_card_input--dateTime"	class="twchr_schedule_card_input" type="datetime-local" value="<?php echo esc_html( $date_time ); ?>">
			<p><?php echo ! empty( $dateTime ) ? esc_html( $date_time ) : ''; ?></p>
		</div>
		
		<label for="twchr_schedule_card_input--duration"><?php twchr_esc_i18n( 'Duration (mins)', 'html' ); ?></label>
		<input id="twchr_schedule_card_input--duration"  name="twchr_schedule_card_input--duration"	class="twchr_schedule_card_input" type="number" value="<?php echo esc_html( $duration ); ?>">
	</div>
	<div class="twchr-card-row serie">	
	
		<label for="twchr_schedule_card_input--serie__name" id="twchr_schedule_card_input--serie__name--label"><?php twchr_esc_i18n( 'Serie', 'html' ); ?></label>
			<div class="twchr_cards_input_badges">
				<select name="twchr_schedule_card_input--serie__name" id="twchr_schedule_card_input--serie__name">
					<option value="null">notting</option>
				</select>
				<badges id="twchr_term_serie_list"><?php echo $term_serie_list; ?></badges>
			</div>
			<p><a target="_blank" href="<?php echo TWCHR_ADMIN_URL . 'edit-tags.php?taxonomy=serie&post_type=twchr_streams&from_cpt_id=' . get_the_id(); ?>"><?php twchr_esc_i18n( 'Create a new serie', 'html' ); ?></a></p>
		</div>
	
	<p id="twchr_twtich_schedule_response" style="display: none;"><?php echo esc_js( $twchr_twicth_schedule_response ); ?><p>
   

</div>

