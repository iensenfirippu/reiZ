# #################### #
# Drop old tables      #
# #################### #

DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS login;
#DROP TABLE IF EXISTS ban;

# #################### #
# Create new tables    #
# #################### #

CREATE TABLE IF NOT EXISTS user
(
	u_id INT(11) NOT NULL AUTO_INCREMENT,
	username VARCHAR(48) NOT NULL,
	password VARCHAR(48) NOT NULL,
	PRIMARY KEY (u_id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS login
(
	l_id INT(11) NOT NULL AUTO_INCREMENT,
	occured INT(11) NOT NULL,
	ip VARCHAR(48) NOT NULL,
	altip VARCHAR(48) NOT NULL,
	username VARCHAR(48) NOT NULL,
	password VARCHAR(48) NOT NULL,
	success INT(1) NOT NULL,
	PRIMARY KEY (l_id)
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;

# #################### #
# Insert test data     #
# #################### #

INSERT INTO user (username, password) VALUES ('philip','ikesmit');
