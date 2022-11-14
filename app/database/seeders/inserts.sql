-- Mot de passe de Paul : paul
INSERT INTO users (username, biography, password, registered_at) VALUES ("Paul","Salut c'est Paul","6c63212ab48e8401eaf6b59b95d816a9","14/11/22 14:00:00");
-- Mot de passe de Mark : 1234
INSERT INTO users (username, biography, password, registered_at) VALUES ("Mark","Salut c'est Mark le voisin de Paul","81dc9bdb52d04dc20036dbd8313ed055","14/11/22 14:00:00");
-- Mot de passe de Jean : mdp 
INSERT INTO users (username, biography, password, registered_at) VALUES ("Jean","Bonjour, moi c'est Jean. Passion de l'image depuis deja quelques annees que j'expose ici meme.","aa36dc6e81e2ac7ad03e12fedcb6a2c0","14/11/22 14:00:00");


INSERT INTO galleries (name, description, nb_pictures, public, registered_at) VALUES ("Paysages","Du monde",11,1,"14/11/22 15:00:00");
INSERT INTO galleries (name, description, nb_pictures, public, registered_at) VALUES ("Galaxy","...",9,0,"14/11/22 15:00:00");
INSERT INTO galleries (name, description, nb_pictures, public, registered_at) VALUES ("Divers","Et autres",6,1,"14/11/22 15:00:00");
INSERT INTO galleries (name, description, nb_pictures, public, registered_at) VALUES ("Digit","Numerique",3,0,"14/11/22 15:00:00");


INSERT INTO pictures (name, descr, link) VALUES ("Oasis","Du vert","/img/img_1.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Aurores","","/img/img_2.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Tout seul","tranquille","/img/img_3.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Foret rouge","C'est beau hein","/img/img_4.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Canyon","Avec plein d'eau dedans","/img/img_5.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Dune","Perso j'ai bien aime le film","/img/img_6.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Montagne","","/img/img_7.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Radiation","Non","/img/img_8.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Neige","On va fermer la fenetre","/img/img_9.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Coucher","","/img/img_10.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Galaxy","","/img/img_11.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Orbite","Sympa","/img/img_12.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Satellite","Super description d'image","/img/img_13.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Mars","Planete rouge il parait","/img/img_14.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Galaxy gros plan","On voit encore mieux","/img/img_15.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Feu","On va ouvrir la fenetre","/img/img_16.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Soleil","Ou je ne sais quoi","/img/img_17.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Petite image","Sans pretention","/img/img_18.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Anneaux","","/img/img_19.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Citron","La bonne limonade","/img/img_20.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Ampoule","Energie verte ou quoi","/img/img_21.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("A l'eau ?","ALLO!?","/img/img_22.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Android Brothers","","/img/img_23.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Check ca","","/img/img_24.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Pingu","","/img/img_25.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Orange","et bleu","/img/img_26.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Rouge","","/img/img_27.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Meduse","Bleu","/img/img_28.jpg");
INSERT INTO pictures (name, descr, link) VALUES ("Antarctique","","/img/img_29.jpg");


INSERT INTO tags (tag) VALUES ("Nature");
INSERT INTO tags (tag) VALUES ("Paysage");
INSERT INTO tags (tag) VALUES ("Montagne");
INSERT INTO tags (tag) VALUES ("Foret");
INSERT INTO tags (tag) VALUES ("Espace");
INSERT INTO tags (tag) VALUES ("Chaud");
INSERT INTO tags (tag) VALUES ("Eau");


INSERT INTO UsersToGalleries (id_user, id_gallery) VALUES (1,1);
INSERT INTO UsersToGalleries (id_user, id_gallery) VALUES (1,2);
INSERT INTO UsersToGalleries (id_user, id_gallery) VALUES (3,3);
INSERT INTO UsersToGalleries (id_user, id_gallery) VALUES (3,4);


INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (1,1);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (1,2);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (1,3);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (1,4);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (1,5);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (1,6);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (1,7);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (1,8);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (1,9);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (1,10);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (1,29);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (2,11);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (2,12);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (2,13);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (2,14);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (2,15);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (2,16);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (2,17);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (2,18);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (2,19);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (3,20);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (3,21);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (3,22);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (3,23);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (3,24);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (3,25);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (4,26);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (4,27);
INSERT INTO GalleriesToPictures (id_gallery, id_picture) VALUES (4,28);


INSERT INTO GalleriesToTags (id_gallery, id_tag) VALUES (1,1);
INSERT INTO GalleriesToTags (id_gallery, id_tag) VALUES (1,2);
INSERT INTO GalleriesToTags (id_gallery, id_tag) VALUES (2,5);


INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (1,4);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (1,7);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (2,3);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (2,1);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (3,2);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (3,1);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (4,2);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (4,6);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (5,2);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (6,2);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (7,4);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (7,7);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (7,3);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (8,1);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (9,2);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (10,1);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (11,3);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (11,6);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (11,2);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (12,2);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (13,2);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (16,6);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (17,2);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (19,2);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (21,4);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (21,6);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (21,2);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (21,3);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (24,2);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (25,6);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (26,2);
INSERT INTO PicturesToTags (id_picture, id_tag) VALUES (27,2);


INSERT INTO UsersAccesses (id_user, id_gallery) VALUES (2,2);
INSERT INTO UsersAccesses (id_user, id_gallery) VALUES (2,4);


