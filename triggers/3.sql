drop trigger if exists check_phone_insert;
delimiter $$
create trigger check_phone_insert before insert on phone_number
for each row
begin

	if new.phone in (select phone from phone_number) then
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Phone number already exists';
	end if;

end$$
delimiter ;

drop trigger if exists check_phone_update;
delimiter $$
create trigger check_phone_update before update on phone_number
for each row
begin

	if new.phone in (select phone from phone_number where VAT != old.VAT) then
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Phone number already exists';
	end if;

end$$
delimiter ;
