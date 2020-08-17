USE PSI;
INSERT INTO user (`name`,`email`,`password`)VALUES 
("Palmera","email@sdas.org","senha"),
("Gabriele","gabi@email.com","123456"),
("Leticia","lele@email.com","123456"),
("Marcele","marcele@email.com","123456"),
("Milton","milton@email.com","123456"),
("Caio","caio@email.com","123456"); 

INSERT INTO authors (`name`,`surname`)VALUES 
("Agatha","Christie"),
("Colleen","Hoover"),
("John","Green"),
("Nathalia","Arcuri"),
("Thiago","Nigro"),
("Jose","de Alencar"),
("Machado","de Assis"),
("Joaquim","Manuel de Macedo"),
("Erico","Verissimo"),
("Clarice","Lispector");

INSERT INTO books (`ISBN`,`title`, `pages`, `aid`)VALUES 
("9788525057013","E nao sobrou nenhum", 390, 1),
("9788525057012","Um caso perdido", 298, 2),
("9788525057011","Cidades de papel", 286, 3),
("9788525057019","Me poupe", 180, 4),
("9788525057018","Do mil ao milhao", 175, 5),
("9788525057078","Senhora", 390, 6),
("9788525057056","Dom Casmurro", 298, 7),
("9788525057059","A moreninha", 286, 8),
("9788525057076","Olhai os lirios do campo", 180, 9),
("9788525057023","A hora da estrela", 175, 10);