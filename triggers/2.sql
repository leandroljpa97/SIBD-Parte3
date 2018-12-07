drop trigger if exists check_vet_insert;
delimiter $$
create trigger check_vet_insert before insert on veterinary
for each row
begin

	if (new.VAT in (select VAT from assistant)) then
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'This person is already an assistant';
	end if;
end$$
delimiter ;

drop trigger if exists check_vet_update;
delimiter $$
create trigger check_vet_update before update on veterinary
for each row
begin

	if new.VAT in (select VAT from assistant) then
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'This person is already an assistant';
	end if;
end$$
delimiter ;

drop trigger if exists check_assistant_insert;
delimiter $$
create trigger check_assistant_insert before insert on assistant
for each row
begin

	if new.VAT in (select VAT from veterinary) then
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'This person is already a veterinary';
	end if;
end$$
delimiter ;

drop trigger if exists check_assistant_update;
delimiter $$
create trigger check_assistant_update before update on assistant
for each row
begin

	if new.VAT in (select VAT from veterinary) then
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'This person is already a veterinary';
	end if;
end$$
delimiter ;
