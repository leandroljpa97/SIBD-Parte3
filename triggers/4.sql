drop function if exists total_nr_consults;

delimiter $$
create function total_nr_consults(a_name varchar(255), a_vat varchar(255), a_year integer)
returns integer
begin
	declare total integer;

	select count(*) into total
	from animal inner join consult on animal.name = consult.name and animal.VAT = consult.VAT_owner
	where year(date_timestamp)=a_year and animal.name = a_name and animal.VAT = a_vat;

	return total;
end $$
delimiter ;
