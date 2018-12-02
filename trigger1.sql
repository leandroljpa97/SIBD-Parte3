/* 1 */
drop trigger if exists update_age;
delimiter $$
create trigger update_age before insert on consult
for each row
begin

	update animal set age = timestampdiff(YEAR, birth_year,new.date_timestamp)
		where animal.name=new.name and animal.VAT=new.VAT_owner ;

end$$
delimiter ;

/* 2 */
drop trigger if exists check_vet_insert;
delimiter $$
create trigger check_vet_insert before insert on veterinary
for each row
begin

	if (new.VAT in (select VAT from assistant)) then
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'This person is already an assistant';
	END IF;

end$$
delimiter ;

drop trigger if exists check_vet_update;
delimiter $$
create trigger check_vet_update before update on veterinary
for each row
begin

	if new.VAT in (select VAT from assistant) then
	signal sqlstate '45000' set message_text = 'This person is already an assistant';
	end if;

end$$
delimiter ;

drop trigger if exists check_assistant_insert;
delimiter $$
create trigger check_assistant_insert before insert on assistant
for each row
begin

	if new.VAT in (select VAT from veterinary) then
		signal sqlstate '45000' set message_text = 'This person is already a veterinary';
	end if;

end$$
delimiter ;

drop trigger if exists check_assistant_update;
delimiter $$
create trigger check_assistant_update before update on assistant
for each row
begin

	if new.VAT in (select VAT from veterinary) then
		signal sqlstate '45000' set message_text = 'This person is already a veterinary';
	end if;

end$$
delimiter ;

/* 3 */
drop trigger if exists check_phone_insert;
delimiter $$
create trigger check_phone_insert before insert on phone_number
for each row
begin

	if new.phone in (select phone from phone_number) then
		signal sqlstate '45000' set message_text = 'Phone number already exists';
	end if;

end$$
delimiter ;

drop trigger if exists check_phone_update;
delimiter $$
create trigger check_phone_update before update on phone_number
for each row
begin

	if new.phone in (select phone from phone_number) then
		signal sqlstate '45000' set message_text = 'Phone number already exists';
	end if;

end$$
delimiter ;


/* 4 testado */
drop function if exists total_nr_consults;

delimiter $$
create function total_nr_consults(a_name varchar(255), a_vat varchar(255), a_year integer)
returns integer
begin

	declare total integer;

	select count(*) into total
	from animal inner join consult on animal.name=consult.name and animal.VAT= consult.VAT_owner
	where year(date_timestamp)=a_year and animal.name = a_name and animal.VAT = a_vat;

	return total;

end $$
delimiter ;


/* 5 testado */
drop procedure if exists change_reference;

delimiter $$
create procedure change_reference()
begin

update indicator left outer join produced_indicator on produced_indicator.indicator_name=indicator.name
	set reference_value = reference_value*0.1,
	units='centigrams',
	value = value*0.1,
	VAT_owner = VAT_owner,
	date_timestamp = date_timestamp,
	num = num,
	produced_indicator.name = produced_indicator.name,
	produced_indicator.indicator_name = produced_indicator.indicator_name
	where units='milligrams';

end $$
delimiter ;
