# #################### #
# Insert test data     #
# #################### #

INSERT INTO <[!PREFIX!]>masterpage (name) VALUES ('default');
INSERT INTO <[!PREFIX!]>masterpage (name) VALUES ('wide');

INSERT INTO <[!PREFIX!]>page (masterpage, name, content, inmenu, weight) VALUES (2,'home','<h1>Welcome welcome, one and all</h1>\n<span>\n\tWelcome to pointless little oop test, it&apos;s a lot of fun...\n\tbut please don&apos;t take it too seriously.\n\tit&apos;s all fun and games until someone gets hurt.\n</span>',1,0);
INSERT INTO <[!PREFIX!]>page (masterpage, name, content, inmenu, weight) VALUES (1,'gallery','',1,1);
INSERT INTO <[!PREFIX!]>page (masterpage, name, content, inmenu, weight) VALUES (1,'files','',1,2);
INSERT INTO <[!PREFIX!]>page (masterpage, name, content, inmenu, weight) VALUES (1,'blog','',1,100);
INSERT INTO <[!PREFIX!]>page (masterpage, name, content, inmenu, weight) VALUES (2,'resume','<h1>Resum&eacute;</h1>\n<span>noget</span>',0,101);

INSERT INTO <[!PREFIX!]>module (name, title) VALUES ('menu','Menu');
INSERT INTO <[!PREFIX!]>module (name, title) VALUES ('breadcrumbs','Breadcrumbs');
INSERT INTO <[!PREFIX!]>module (name, title) VALUES ('blog','Blog');
INSERT INTO <[!PREFIX!]>module (name, title) VALUES ('gallery','Gallery');
INSERT INTO <[!PREFIX!]>module (name, title) VALUES ('files','Files');

INSERT INTO <[!PREFIX!]>masterpage_module (mp_id, m_id) VALUES (1,1);
INSERT INTO <[!PREFIX!]>masterpage_module (mp_id, m_id) VALUES (1,2);
INSERT INTO <[!PREFIX!]>masterpage_module (mp_id, m_id) VALUES (2,1);
INSERT INTO <[!PREFIX!]>masterpage_module (mp_id, m_id) VALUES (2,2);

INSERT INTO <[!PREFIX!]>page_module (p_id, m_id, location, method) VALUES (1,3,'','frontpage');
INSERT INTO <[!PREFIX!]>page_module (p_id, m_id, location, method) VALUES (2,4,'','');
INSERT INTO <[!PREFIX!]>page_module (p_id, m_id, location, method) VALUES (3,5,'','');
INSERT INTO <[!PREFIX!]>page_module (p_id, m_id, location, method) VALUES (4,3,'','');
