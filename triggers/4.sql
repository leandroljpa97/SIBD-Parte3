drop function if exists total_nr_consults;

delimiter $$
create function total_nr_consults(a_name varchar(255), a_vat varchar(255), a_year integer)
returns integer
begin
	declare total integer;

	select count(*) into total
	from consult
	where year(date_timestamp) = a_year and name = a_name and VAT_owner = a_vat;

	return total;
end $$
delimiter ;
