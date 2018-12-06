drop procedure if exists change_reference;

delimiter $$
create procedure change_reference()
begin

update indicator left outer join produced_indicator on produced_indicator.indicator_name = indicator.name
	set reference_value = reference_value*0.1,
	units = 'centigrams',
	value = value*0.1,
	VAT_owner = VAT_owner,
	date_timestamp = date_timestamp,
	num = num,
	produced_indicator.name = produced_indicator.name,
	produced_indicator.indicator_name = produced_indicator.indicator_name
	where units='milligrams';

end $$
delimiter ;
