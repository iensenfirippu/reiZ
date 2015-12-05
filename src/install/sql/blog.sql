# #################### #
# Drop old tables      #
# #################### #

DROP TABLE IF EXISTS <[!PREFIX!]>blogpost;
DROP TABLE IF EXISTS <[!PREFIX!]>blogcategory;
DROP TABLE IF EXISTS <[!PREFIX!]>blogtag;
DROP TABLE IF EXISTS <[!PREFIX!]>blogposttag;

# #################### #
# Create new tables    #
# #################### #

CREATE TABLE IF NOT EXISTS <[!PREFIX!]>blogpost
(
	p_id INT(11) NOT NULL AUTO_INCREMENT,
	posted INT(11) NOT NULL,
	category INT(11) NOT NULL,
	title VARCHAR(48) NOT NULL,
	shorttext VARCHAR(255) NOT NULL,
	content BLOB NOT NULL,
	PRIMARY KEY (p_id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS <[!PREFIX!]>blogcategory
(
	c_id INT(11) NOT NULL AUTO_INCREMENT,
	name VARCHAR(48) NOT NULL,
	title VARCHAR(48) NOT NULL,
	PRIMARY KEY (c_id)
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS <[!PREFIX!]>blogtag
(
	t_id INT(11) NOT NULL AUTO_INCREMENT,
	name VARCHAR(20) NOT NULL,
	PRIMARY KEY (t_id)
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS <[!PREFIX!]>blogposttag
(
	p_id VARCHAR(36) NOT NULL,
	t_id VARCHAR(36) NOT NULL,
	PRIMARY KEY (p_id, t_id)
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS <[!PREFIX!]>blogpostimage
(
	p_id VARCHAR(36) NOT NULL,
	link VARCHAR(100) NOT NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;

# #################### #
# Insert test data     #
# #################### #

INSERT INTO <[!PREFIX!]>blogcategory (name, title) VALUES ('nihongo','日本語');
INSERT INTO <[!PREFIX!]>blogcategory (name, title) VALUES ('english','English');
INSERT INTO <[!PREFIX!]>blogcategory (name, title) VALUES ('dansk','Dansk');

INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1378961609,1,'日本語のテストです','焣䨺䣥 土槥 ち.じゅせ榚 とれにゃ窣奟, 禯穨苦ス解 訧卤さ䤣奎 ヴィ秦ちゃ 坩堥, 查䦦 䤩け禞 とれにゃ窣奟 べん㛤づ詧 鰥槣䤨焣䨺 びょ土槥觧ヴ 秦ちゃµす짦 䧪プ へ觃グィ, 蟤ず槎䧪プ 訣㠨きゅ椢鏥 禯穨苦ス解 秵リョ極 ず槎 楃廥 さ䤣奎 ひょボあ棃䰯 訣㠨きゅ椢鏥 坩堥滦 ち.じゅせ榚 ぴゅ䤩け禞天 榣め婃奤夺 拥䪦 ま楃廥 鎌詃でな餥 ぴビこ囨む は詪, とれにゃ窣奟 訣㠨きゅ椢鏥 檨壎ぎちょヴィ 䝣ろ がラ゜べ ゑ揧ぎょ 榧檣 べん㛤づ詧 .滣ぬぼにゅ。。。','焣䨺䣥 土槥 ち.じゅせ榚 とれにゃ窣奟, 禯穨苦ス解 訧卤さ䤣奎 ヴィ秦ちゃ 坩堥, 查䦦 䤩け禞 とれにゃ窣奟 べん㛤づ詧 鰥槣䤨焣䨺 びょ土槥觧ヴ 秦ちゃµす짦 䧪プ へ觃グィ, 蟤ず槎䧪プ 訣㠨きゅ椢鏥 禯穨苦ス解 秵リョ極 ず槎 楃廥 さ䤣奎 ひょボあ棃䰯 訣㠨きゅ椢鏥 坩堥滦 ち.じゅせ榚 ぴゅ䤩け禞天 榣め婃奤夺 拥䪦 ま楃廥 鎌詃でな餥 ぴビこ囨む は詪, とれにゃ窣奟 訣㠨きゅ椢鏥 檨壎ぎちょヴィ 䝣ろ がラ゜べ ゑ揧ぎょ 榧檣 べん㛤づ詧 .滣ぬぼにゅ

みゅぐ ウェぱをしじゃ 䩩蛣うぢゃら レ゜しゅみ ち.じゅせ榚 ひょボあ棃䰯 りょじ饊ひゅ苯 榥へ ウェぱを, ち.じゅ とれにゃ窣奟 ひゃㄦ礊りセ きゃ谦 あ棃䰯 訧卤さ䤣奎 ジュ奚ゞギュた 䨵キぢょつみゅ 秦ちゃ 褦襃狨 苦ス 揧ぎょ蝥果积 褦襃狨䩨拣 褦襃狨 詃で 禧秚ぢゅ妣䝣 びゅ䦞狦チョ訤 䨵キぢょつみゅ, 揧ぎょ蝥果积 廥ぶみゃねレ゜ 獧ぞ祟 荤裃 黨竤きょ 绨の嫣どりゅ 襟䧞嶣ぴょほ 䩦蟤

いま 黧ぴゃピャ橣堨 ビャ亜き饟びゃ ぴビこ囨む ゞギュた とれにゃ窣奟 ぐげ䣊姟ざ 訤訧 䦧䋯ブ, 裃スィか ち.じゅせ榚 ひゃㄦ礊りセ 襟䧞嶣ぴょほ 榧檣, 鎌詃でな餥 查䦦ルがラ゜ びょ土槥觧ヴ マギりょ 亜き 蛣うぢゃ .滣ぬぼにゅ しゅみ坩堥滦 䩩蛣うぢゃら 樃背, 脩鰥 䦞狦チョ 訣㠨きゅ椢鏥 黧ぴゃピャ橣堨 祣拥䪦	てゑ 姟ざ 查䦦ル 榣め婃奤夺 䦧䋯ブ餩ふ ひょボ がラ゜べ .滣ぬぼにゅ 廥ぶみゃねレ゜ ジュ奚ゞギュた, びゅ䦞 べん㛤づ詧 黧ぴゃピャ橣堨 にゅ訣㠨

ちょヴィ べん㛤づ詧 鰥槣䤨焣䨺 鏧奯奊, 㠣黨竤きょ盤 䋩誨卣郎𐤦 檨壎ぎちょヴィ ぱを 蛣うぢゃ, け禞 黧ぴゃピャ橣堨 訧卤さ䤣奎 滣ぬぼ ぴょほびゅ ぷお갤䧣䄥 䦧䋯ブ餩ふ りゃだ姌樃背 揧ぎょ, 查䦦ルがラ゜ 訣㠨きゅ椢鏥 ち.じゅ 樃背, 獧ぞ祟マギ 褦襃狨䩨拣 ぐげ䣊姟ざ マギりょ 姎䦨 蟤ず槎䧪プ しゅみ坩堥滦 秦ちゃµす짦 µす 䣊姟ざ, がラ゜べ 訤訧 ビャ亜き饟びゃ 䯞ホ䄦姤荤 きゃ谦櫦やソ 蟤ず槎䧪プ べん㛤づ詧 レ゜しゅみ ひょボ

獧ぞ祟 饟びゃ ウェぱをしじゃ 褦襃狨䩨拣, ぴゅ䤩け禞天 檨壎ぎちょヴィ りゃだ姌樃背 䄦姤荤 堨ビャ らぴビ め婃 绨の嫣どりゅ 裃スィか蝣蟨 䨵キぢょつみゅ, 棌睢い 榥へ ぷお갤䧣䄥 禧秚ぢゅ妣䝣 ム鏧奯奊婨 ろ姎䦨盯갣 棌睢いま楃 饟びゃ 䋩誨卣, じ饊ひゅ 积襟 きゃ谦櫦やソ 黧ぴゃピャ橣堨 棌睢いま楃 とれにゃ 䣥にゆ妤フォ 訣㠨きゅ椢鏥 褦襃狨䩨拣 䝣ろ, 榥へ 查䦦ル 褦襃狨䩨拣 りゃだ姌樃背 裃スィか蝣蟨, 积襟 しじゃ榥 查䦦ルがラ゜ ウェぱをしじゃ しじゃ榥 鎌詃でな餥 訧卤さ䤣奎 秦ちゃµす짦 榚㠣

ちゅ駣りゃ 榥へ觃グィ詎 褦襃狨䩨拣 ぴビこ囨む 椢鏥 べん㛤づ詧 .滣ぬぼにゅ 壎ぎちょ 姎䦨, 脩鰥 天绨の 禧秚ぢゅ妣䝣 䦧䋯ブ餩ふ しじゃ榥 裌祦秵リョ極 訣㠨きゅ椢鏥 げ䣊 解榣 查䦦ルがラ゜ 䦧䋯ブ餩ふ 妦椩にょ 亜き 窣奟ぴゅ 禯穨苦ス解 ジュ奚ゞギュた 褦襃狨䩨拣, ち.じゅせ榚 蟤ず槎䧪プ 䣥にゆ妤フォ さ䤣奎 きょ盤。');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1378971609,1,'また日本語のテスト','䯞ホ 榥へ觃グィ詎 䨵キぢょつみゅ 鎌詃で, 㠣黨竤きょ盤 ろ姎䦨盯갣 びゅ䦞狦チョ訤 きゃ谦櫦 土槥, 脩鰥 へ觃グィ 查䦦ルがラ゜ 䣥にゆ妤フォ ぴゅ䤩け禞天 しゅみ坩堥滦 樃背 查䦦ル, びょ土槥觧ヴ 褦襃狨䩨拣 裃スィか蝣蟨 黨竤きょ ぶみゃ 盤ひょボ 查䦦ルがラ゜ ウェぱをしじゃ 秦ちゃµす짦 ぷお, 焣䨺䣥 亜き 㠣黨竤きょ盤 びゅ䦞狦チョ訤 ビャ亜き饟びゃ ジュ奚ゞギュた あ棃 查䦦ル, とれにゃ窣奟 廥ぶみゃねレ゜ ぐげ䣊姟ざ 查䦦 窣奟ぴゅ, がラ゜べ 奤夺 びょ土槥觧ヴ。。。','䯞ホ 榥へ觃グィ詎 䨵キぢょつみゅ 鎌詃で, 㠣黨竤きょ盤 ろ姎䦨盯갣 びゅ䦞狦チョ訤 きゃ谦櫦 土槥, 脩鰥 へ觃グィ 查䦦ルがラ゜ 䣥にゆ妤フォ ぴゅ䤩け禞天 しゅみ坩堥滦 樃背 查䦦ル, びょ土槥觧ヴ 褦襃狨䩨拣 裃スィか蝣蟨 黨竤きょ ぶみゃ 盤ひょボ 查䦦ルがラ゜ ウェぱをしじゃ 秦ちゃµす짦 ぷお, 焣䨺䣥 亜き 㠣黨竤きょ盤 びゅ䦞狦チョ訤 ビャ亜き饟びゃ ジュ奚ゞギュた あ棃 查䦦ル, とれにゃ窣奟 廥ぶみゃねレ゜ ぐげ䣊姟ざ 查䦦 窣奟ぴゅ, がラ゜べ 奤夺 びょ土槥觧ヴ 訣㠨きゅ椢鏥 びゅ䦞狦チョ訤 とれにゃ窣奟 .滣ぬぼにゅ 訣㠨きゅ椢鏥 フォびょ ウェぱを 禯穨 滣ぬぼ ぴゅ䤩け禞天 ギェ代榧檣䩦 びゅ䦞狦チョ訤, 䯞ホ 揧ぎょ蝥果积 しゅみ坩堥滦 槎䧪プ, みゅぐげ 䋯ブ ひょボあ棃䰯 蟤ず槎䧪プ

壎ぎ 苦ス解 褦襃狨䩨拣 䩩蛣うぢゃら 䨵キぢょつみゅ, 奚ゞ 裌祦秵リョ極 ム鏧奯奊婨 な餥ギェ 樃背 襟䧞嶣 褦襃狨䩨拣 裃スィか蝣蟨, 䩦蟤ず べん㛤づ詧 りゃだ姌樃背 だ姌, ゞギュた 查䦦 .滣ぬぼにゅ 䩩蛣うぢゃら ぴょほびゅ 䤣奎 とれにゃ窣奟 秦ちゃµす짦, フォびょ土 榚㠣 りょじ饊ひゅ苯 䦧䋯ブ餩ふ ぐげ䣊姟ざ, 䣊姟ざ れにゃ 查䦦ルがラ゜ 廥ぶみゃねレ゜ 禧秚ぢゅ妣䝣 揧ぎょ蝥果积 廥ぶみゃねレ゜ あ棃 やソひゃ, スィか びょ土槥觧ヴ 裃スィか蝣蟨 きゃ谦櫦 獧ぞ ぴゅ䤩け禞天 䋩誨卣郎𐤦 榥へ觃グィ詎 せ榚㠣, 槎䧪プ 䋯ブ 䩩蛣うぢゃら 䯞ホ䄦姤荤, ん㛤づ ち.じゅせ榚 べん㛤づ詧 蝥果 とれにゃ 極鎌 棌睢いま楃 䨵キぢょつみゅ, ち.じゅせ榚 ひゃㄦ礊りセ 脩鰥 嫣どりゅ

䋯ブ きゅ椢鏥 蟤ず槎䧪プ 妦椩にょちゅ駣 .滣ぬぼにゅ 禯穨苦ス解 褦襃狨䩨拣 µす짦 しじゃ, け禞 檨壎ぎちょヴィ 秦ちゃµす짦 ん㛤づ 𐤦祣 ち.じゅせ榚 びょ土槥觧ヴ 檨壎ぎちょヴィ ぶみゃね は詪脩 くもは詪脩 妦椩にょちゅ駣 げ䣊, きゅ椢鏥 ぴゅ䤩け禞天 べん㛤づ詧 䋩誨, 獧ぞ祟 ひゃㄦ礊りセ ビャ亜き饟びゃ ぐげ䣊姟ざ ぢょつ

きゅ椢鏥 訤訧 べん㛤づ詧 祣拥䪦	てゑ, きゃ谦櫦 りセ 禧秚ぢゅ妣䝣 びゅ䦞狦チョ訤 訧卤さ䤣奎 䦞狦チョ 禯穨苦ス解 ビャ亜き饟びゃ 䩩蛣 ま楃廥 訧卤さ䤣奎 䯞ホ䄦姤荤 积襟, キぢょつ 拥䪦 棌睢いま楃 ジュ奚ゞギュた 䯞ホ䄦姤荤 訣㠨きゅ椢鏥 䦧䋯ブ餩ふ とれにゃ 荤裃 ぢゅ妣 棌睢い びょ土槥觧ヴ 棌睢いま楃 ジュ奚ゞギュた, 绨の嫣どりゅ くもは詪脩 棌睢いま楃 蝣蟨䨵 うぢゃ レ゜しゅみ 禯穨苦ス解 䨵キぢょつみゅ ぬぼ。');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1378981609,2,'Another post in English','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et purus et libero luctus molestie. Praesent quis scelerisque sapien. Phasellus consectetur turpis ac enim tempus, vel facilisis enim pellentesque. Aliquam eu placerat massa. Fusce ultrices...','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et purus et libero luctus molestie. Praesent quis scelerisque sapien. Phasellus consectetur turpis ac enim tempus, vel facilisis enim pellentesque. Aliquam eu placerat massa. Fusce ultrices justo id lacinia laoreet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam ac orci justo. Curabitur sagittis erat nec sodales dictum. Aenean vitae nunc vel diam ullamcorper sollicitudin at sit amet tortor. Aliquam sit amet tincidunt sem.

Morbi in pulvinar velit. Nunc egestas feugiat blandit. Duis rutrum in orci et ultrices. Quisque in nulla orci. Ut eu tristique dolor. Sed consequat dolor ligula, vitae vulputate ipsum tempor a. Proin bibendum suscipit risus eget molestie. Nunc eu nibh et neque pulvinar suscipit. Phasellus volutpat dolor enim, nec imperdiet eros tristique non. Integer hendrerit velit non feugiat posuere. Curabitur rutrum neque id ante lobortis volutpat. Curabitur in rhoncus lorem. Cras vel neque sit amet nisl placerat sollicitudin sit amet mollis lacus.

Aenean vel arcu imperdiet, tristique dolor sed, mattis massa. Morbi ut sem non velit mollis semper vitae imperdiet purus. Quisque a viverra diam. Duis id mi nec odio ultrices congue in id dui. Aenean sit amet dui velit. Aliquam erat volutpat. Pellentesque eget neque justo. Duis vel nibh non dui ullamcorper consequat. Quisque vestibulum, augue eget tempor laoreet, libero justo pretium magna, in aliquet nisl tellus id lorem. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin feugiat metus vitae gravida ultricies. Donec lacinia mi in mi tincidunt tempor. Nullam tempus quam velit, a aliquet nisi ultricies ac. Quisque malesuada eu urna eu aliquam. Aliquam a neque porta, sagittis libero sit amet, fringilla purus. Nunc quis leo ac libero scelerisque posuere a sit amet eros.');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1378991609,2,'This is a test in English','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sollicitudin elementum lacus, quis semper nisi varius vel. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean sed vehicula lacus, pharetra...', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sollicitudin elementum lacus, quis semper nisi varius vel. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean sed vehicula lacus, pharetra tempor magna. Aenean lorem erat, consequat at magna non, bibendum commodo turpis. Nullam egestas rhoncus lorem at aliquet. Nunc laoreet sapien sem, sit amet porta ligula molestie in. Ut vitae augue et arcu facilisis volutpat. Ut cursus quis metus non venenatis. Nunc eu nunc mauris. Nulla hendrerit pulvinar ante ut semper. Duis vel ante consequat, scelerisque nisi quis, hendrerit quam. Mauris felis est, auctor eu tortor quis, ultricies accumsan justo. Donec dignissim cursus leo vulputate vehicula.

Duis condimentum tincidunt nulla, id mattis nisi dapibus sit amet. Morbi pulvinar quam enim. Maecenas eu lacus sit amet dui facilisis viverra non vitae lacus. Aliquam erat volutpat. Nunc blandit velit ut egestas egestas. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam consequat dapibus tortor, pulvinar dictum justo interdum id.

Aliquam interdum venenatis lectus eget consequat. Praesent bibendum varius leo vel malesuada. Cras interdum neque in est varius, nec ornare neque venenatis. Fusce gravida felis ut lectus consequat sodales. Nullam imperdiet risus et odio tristique tristique. In interdum eget purus vel malesuada. Nam facilisis molestie odio, et porttitor lectus sollicitudin at. Sed ultricies erat sed elementum lobortis. Vestibulum magna nisi, commodo vel ultrices eu, dictum a nisi. Etiam eu sodales mauris. Sed a turpis id ante ultrices varius in eget tortor. Sed nec lacus eget magna sollicitudin vestibulum. Morbi tempor eros et nibh pharetra cursus. Pellentesque vitae turpis vestibulum, cursus magna iaculis, ultrices turpis. Praesent vulputate imperdiet tellus at sodales. Donec consectetur, sem in dapibus laoreet, neque nulla luctus urna, et dapibus enim ligula vel dui. ');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379001609,3,'Ikke rigtigt dansk men...','...du kommt mir spanisch for...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379011609,3,'...nu med dansk','Det danske sprog er bare et proof-of-concept... desværre vides det ikke længere hvad de forsøgte at bevise...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379021609,3,'Disse danske posts...','Disse danske posts er faktisk bare for at teste sidevælgeren...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379031609,3,'Bullshit','ach du mere lieber... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379041609,3,'Mere bullshit','ach du mere lieber... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379051609,3,'Endnu mere bullshit','ach du endnu mere lieber... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379061609,3,'Abekat','ah mein güte... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379071609,3,'Mere abekat','ah mein mere güte... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379081609,3,'Endnu mere abekat','ah mein endnu mere güte... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379091609,3,'Ikke rigtigt dansk men...','...du kommt mir spanisch for...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379101609,3,'...nu med dansk','Det danske sprog er bare et proof-of-concept... desværre vides det ikke længere hvad de forsøgte at bevise...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379111609,3,'Disse danske posts...','Disse danske posts er faktisk bare for at teste sidevælgeren...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379121609,3,'Bullshit','ach du mere lieber... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379121609,3,'Mere bullshit','ach du mere lieber... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379131609,3,'Endnu mere bullshit','ach du endnu mere lieber... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379141609,3,'Abekat','ah mein güte... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379151609,3,'Mere abekat','ah mein mere güte... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379161609,3,'Endnu mere abekat','ah mein endnu mere güte... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379171609,3,'Ikke rigtigt dansk men...','...du kommt mir spanisch for...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379181609,3,'...nu med dansk','Det danske sprog er bare et proof-of-concept... desværre vides det ikke længere hvad de forsøgte at bevise...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379191609,3,'Disse danske posts...','Disse danske posts er faktisk bare for at teste sidevælgeren...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379201609,3,'Bullshit','ach du mere lieber... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379211609,3,'Mere bullshit','ach du mere lieber... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379221609,3,'Endnu mere bullshit','ach du endnu mere lieber... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379231609,3,'Abekat','ah mein güte... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379241609,3,'Mere abekat','ah mein mere güte... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379251609,3,'Endnu mere abekat','ah mein endnu mere güte... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379361609,3,'Endnu mere abekat','ah mein endnu mere güte... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379371609,3,'Ikke rigtigt dansk men...','...du kommt mir spanisch for...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379381609,3,'...nu med dansk','Det danske sprog er bare et proof-of-concept... desværre vides det ikke længere hvad de forsøgte at bevise...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379391609,3,'Disse danske posts...','Disse danske posts er faktisk bare for at teste sidevælgeren...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379401609,3,'Bullshit','ach du mere lieber... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379411609,3,'Mere bullshit','ach du mere lieber... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379421609,3,'Endnu mere bullshit','ach du endnu mere lieber... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379431609,3,'Abekat','ah mein güte... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379441609,3,'Mere abekat','ah mein mere güte... æøå...', '');
INSERT INTO <[!PREFIX!]>blogpost (posted,category,title,shorttext,content) VALUES (1379451609,3,'Endnu mere abekat','ah mein endnu mere güte... æøå...', '');

INSERT INTO <[!PREFIX!]>blogtag (name) VALUES ('programming');
INSERT INTO <[!PREFIX!]>blogtag (name) VALUES ('venting');
INSERT INTO <[!PREFIX!]>blogtag (name) VALUES ('language');
INSERT INTO <[!PREFIX!]>blogtag (name) VALUES ('cooking');
INSERT INTO <[!PREFIX!]>blogtag (name) VALUES ('games');
INSERT INTO <[!PREFIX!]>blogtag (name) VALUES ('television');
INSERT INTO <[!PREFIX!]>blogtag (name) VALUES ('eskimo');
INSERT INTO <[!PREFIX!]>blogtag (name) VALUES ('jinpunkanbun');
INSERT INTO <[!PREFIX!]>blogtag (name) VALUES ('yakamashii');
INSERT INTO <[!PREFIX!]>blogtag (name) VALUES ('rubiks');

INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (1,7);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (1,8);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (2,2);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (2,4);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (3,1);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (3,3);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (4,2);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (4,6);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (5,3);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (5,10);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (6,3);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (6,10);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (7,2);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (7,3);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (8,4);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (8,5);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (9,6);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (9,7);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (10,8);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (10,9);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (11,2);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (11,4);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (12,7);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (12,10);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (12,4);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (13,10);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (13,2);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (14,1);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (15,2);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (16,3);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (17,4);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (18,5);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (19,6);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (20,7);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (21,8);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (22,9);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (23,10);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (24,1);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (25,2);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (26,3);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (27,4);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (28,5);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (29,6);
INSERT INTO <[!PREFIX!]>blogposttag (p_id,t_id) VALUES (30,7);
