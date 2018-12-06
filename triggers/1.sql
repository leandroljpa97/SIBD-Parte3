drop trigger if exists update_age;
delimiter $$
create trigger update_age after insert on consult
for each row
begin
	declare max_date date;

	select LEAST(NOW(), max(date_timestamp)) into max_date
	from consult
	where name=new.name and VAT_owner=new.VAT_owner;

	update animal set age = timestampdiff(YEAR, birth_year, max_date), animal.name=animal.name, animal.VAT=animal.VAT
	where animal.name=new.name and animal.VAT=new.VAT_owner;

end$$
delimiter ;
