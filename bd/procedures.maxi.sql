use maxi;

delimiter ; 

drop procedure if exists Select_employe_avec_hash;

delimiter //

create procedure Select_employe_avec_hash(in p_id_hash varchar(45))
begin
	select E.id_employes, E.prenom, E.nom, E.date_naissance, E.id_hash, E.hash, S.id_sexe, S.nom as nom_sexe, 
		R.id_role, R.nom as nom_role, P.id_poste, P.nom as nom_poste, D.id_departement,
		D.nom as nom_departement, E.actif from employes E
		left join sexe S on E.id_sexe = S.id_sexe
		left join role R on E.id_role = R.id_role
		left join poste P on E.id_poste = P.id_poste
		left join departement D on P.id_departement = D.id_departement
        where E.id_hash = p_id_hash or p_id_hash is null;

end //

delimiter ;

drop procedure if exists Select_employe_avec_id;

delimiter //

create procedure Select_employe_avec_id(in p_id int)
begin
	select E.id_employes, E.prenom, E.nom, E.date_naissance, E.id_hash, E.hash, S.id_sexe, S.nom as nom_sexe, 
		R.id_role, R.nom as nom_role, P.id_poste, P.nom as nom_poste, D.id_departement,
		D.nom as nom_departement, E.actif from employes E
		left join sexe S on E.id_sexe = S.id_sexe
		left join role R on E.id_role = R.id_role
		left join poste P on E.id_poste = P.id_poste
		left join departement D on P.id_departement = D.id_departement
        where E.id_employes = p_id or p_id is null;

end //

delimiter ;

drop procedure if exists Select_marque;

delimiter //

create procedure Select_marque(in p_id_marque int)
begin
	select M.* from marque M
        where M.id_marque = p_id_marque or p_id_marque is null;

end //

delimiter ;

drop procedure if exists Select_sexe;

delimiter //

create procedure Select_sexe(in p_id_sexe int)
begin
	select S.* from sexe S
        where S.id_sexe = p_id_sexe or p_id_sexe is null;

end //

delimiter ;

drop procedure if exists Select_role;

delimiter //

create procedure Select_role(in p_id_role int)
begin
	select R.* from role R
        where R.id_role = p_id_role or p_id_role is null;

end //

delimiter ;

drop procedure if exists Select_poste;

delimiter //

create procedure Select_poste(in p_id_poste int)
begin
	select P.*, D.nom as nom_departement from poste P
		left join departement D on P.id_departement = D.id_departement
        where P.id_poste = p_id_poste or p_id_poste is null;

end //

delimiter ;

drop procedure if exists Select_produit;

delimiter //

create procedure Select_produit(in p_id_produit int)
begin
	select P.id_produit, P.nom, P.prix, P.quantite, M.id_marque, M.nom as nom_marque, P.actif from produit P
		left join marque M on P.id_marque = M.id_marque
        where P.id_produit = p_id_produit or p_id_produit is null;

end //

delimiter ;

drop procedure if exists Select_panier;

delimiter //

create procedure Select_panier(in p_id_panier int)
begin
	select P.* from panier P
        where P.id_panier = p_id_panier or p_id_panier is null;

end //

delimiter ;

drop procedure if exists Select_produit_panier;

delimiter //

create procedure Select_produit_panier(in p_id_panier int)
begin
	select PaPo.id_panier, Pa.date_creation, Po.id_produit, Po.nom as nom_produit, Po.prix, Po.quantite as quantite_restante, M.id_marque, M.nom as nom_marque, Po.actif as produit_actif, 
		PaPo.quantite as quantite_achetee, E.id_employes, E.prenom, E.nom, E.date_naissance, E.id_hash, E.hash, S.id_sexe, S.nom as nom_sexe, R.id_role, R.nom as nom_role, P.id_poste,
        P.nom as nom_poste, D.id_departement, D.nom as nom_departement, E.actif as employe_actif, Pa.actif from produit Po
        left join panier_produit PaPo on PaPo.id_produit = Po.id_produit
		left join marque M on Po.id_marque = M.id_marque
        left join panier Pa on PaPo.id_panier = Pa.id_panier
        left join employes E on Pa.id_employe = E.id_employes
		left join sexe S on E.id_sexe = S.id_sexe
		left join role R on E.id_role = R.id_role
		left join poste P on E.id_poste = P.id_poste
		left join departement D on P.id_departement = D.id_departement
        
        where PaPo.id_panier = p_id_panier;

end //

delimiter ;

drop procedure if exists Ajouter_produit;

delimiter //

create procedure Ajouter_produit(in p_id_marque int,
	in p_nom_produit varchar(150),
	in p_prix decimal(10,2),
    in p_quantite int)
begin
	insert into produit values (null, p_id_marque, p_nom_produit, p_prix, p_quantite, 1);
	call Select_produit(last_insert_id());

end //

delimiter ;

drop procedure if exists Ajouter_employe;

delimiter //

create procedure Ajouter_employe(in p_id_sexe int,
	in p_id_role int,
    in p_id_poste int,
	in p_prenom varchar(150),
    in p_nom varchar(150),
    in p_date_naissance date,
    in p_id_hash varchar(45),
    in p_hash text)
begin
	insert into employes values (null, p_id_sexe, p_id_role, p_id_poste, p_prenom, p_nom, p_date_naissance, p_id_hash, p_hash, 1);
	call Select_employe_avec_id(last_insert_id());

end //

delimiter ;

drop procedure if exists Select_id_employe;

delimiter //

create procedure Select_id_employe(in p_id_hash varchar(45))
begin

	select E.id_employes from employes E where E.id_hash = p_id_hash;

end //

delimiter ;

drop procedure if exists Ajouter_panier;

delimiter //

create procedure Ajouter_panier(in p_id_employe int)
begin
	insert into panier values (null, p_id_employe, current_date(), 1);
	select * from panier P where P.id_panier = last_insert_id();

end //

delimiter ;

drop procedure if exists Lier_produit_a_panier;

delimiter //

create procedure Lier_produit_a_panier(in p_id_panier int,
	in p_id_produit int, 
    in p_quantite_achetee int,
    in p_quantite_restante int)
begin
	set FOREIGN_KEY_CHECKS=0;
	insert into panier_produit values (p_id_panier, p_id_produit, p_quantite_achetee);
	update produit P set P.quantite = p_quantite_restante where P.id_produit = p_id_produit;

end //

delimiter ;

drop procedure if exists Desactiver_employe;

delimiter //

create procedure Desactiver_employe(in p_id_employe int)
begin

	update employes E set E.actif = case
	when E.actif = 0 then 1  
	when E.actif = 1 then 0 
	end
    where E.id_employes = p_id_employe;
    call Select_employe_avec_id(p_id_employe);

end //

delimiter ;

drop procedure if exists Desactiver_produit;

delimiter //

create procedure Desactiver_produit(in p_id_produit int)
begin

	update produit P set P.actif = case
	when P.actif = 0 then 1  
	when P.actif = 1 then 0 
	end
    where P.id_produit = p_id_produit;
	call Select_produit(p_id_produit);

end //

delimiter ;

drop procedure if exists Desactiver_panier;

delimiter //

create procedure Desactiver_panier(in p_id_panier int)
begin

	update panier P set P.actif = case 
	when P.actif = 0 then 1  
	when P.actif = 1 then 0 
	end
	where P.id_panier = p_id_panier;
	call Select_panier(p_id_panier);
    
end //

delimiter ;

drop procedure if exists Journaliser;

delimiter //

create procedure Journaliser(in p_id_action int,
							in p_id_employe int,
                            in p_date datetime,
                            in p_message text)
begin

	insert into journalisation values (null, p_id_action, p_id_employe, p_date, p_message);

end //

delimiter ;

drop procedure if exists Select_actions_employe;

delimiter //

create procedure Select_actions_employe()
begin
	select J.id_employe, E.nom, E.prenom, J.date, A.nom as nom_action, J.message from journalisation J
	left join employes E on E.id_employes = J.id_employe
    left join action A on A.id_action = J.id_action
    order by J.date;

end //

delimiter ;

drop procedure if exists Compter_actions_employe;

delimiter //

create procedure Compter_actions_employe(in p_id_employe int,
										in p_date1 date,
                                        in p_date2 date)
begin
	select A.id_action, J.id_employe, A.nom as nom_action, count(A.nom) as nombre_action from action A
	left join journalisation J on A.id_action = J.id_action
	left join employes E on E.id_employes = J.id_employe
    where (J.date between p_date1 and p_date2) and J.id_employe = p_id_employe
    group by J.id_employe, A.id_action, nom_action;

end //

delimiter ;

