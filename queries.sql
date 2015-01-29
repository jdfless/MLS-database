# Major League Soccer Database Creation Queries -- Project by: Jonathan Flessner for OSU course CS275. March 2014

CREATE TABLE teams (
id int NOT NULL AUTO_INCREMENT,
name varchar(50) NOT NULL,
division varchar(4), # East or West only, can be null for expansion teams
founded int(4),
joinedMLS int(4),
stadium varchar(50) NOT NULL,
numUSOpenCups int(2), # number of US Open Cups, this crowns the best US team.  Open to any team who qualifies, not MLS exclusive.
numSS int(2), # number of supporters shields, this is the regular season champion
numMLSCups int(2), # number of MLS Cups, this is the playoff champion
UNIQUE (name),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- INSERT INTO teams(name, division, founded, joinedMLS, stadium, numUSOpenCups, numSS, numMLSCups) VALUES ([team name], [east/west], [founding date], [MLS join date], [home stadium], [US Open Cups], [Supp. Shields], [MLS Cups]);

CREATE TABLE players (
id int NOT NULL AUTO_INCREMENT,
name varchar(50) NOT NULL,
dob date,
team varchar(50),
UNIQUE (name),
PRIMARY KEY (id),
FOREIGN KEY (team) REFERENCES teams(name) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB;

-- INSERT INTO players(name, dob, team) VALUES ([Player Name], [Birthday], [Current Team]);

CREATE TABLE coaches (
id int NOT NULL AUTO_INCREMENT,
name varchar(50) NOT NULL,
dob date,
team varchar(50),
UNIQUE (name),
UNIQUE (team),
PRIMARY KEY (id),
FOREIGN KEY (team) REFERENCES teams(name) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB;

-- INSERT INTO coaches(name, dob, team) VALUES ([Coach Name], [Birthday], [Current Team]);

CREATE TABLE stadiums (
id int NOT NULL AUTO_INCREMENT,
name varchar (50) NOT NULL,
city varchar (50) NOT NULL,
avgAttendance int, # for the last complete season only
team varchar (50) NOT NULL,
UNIQUE (team),
PRIMARY KEY (id),
FOREIGN KEY (team) REFERENCES teams(name) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB;

-- INSERT INTO stadiums(name, city, avgAttendance, team) VALUES ([stadium name], [city located], [average attendance last full season], [team name]);

# playerTeams and coachTeams will hold previous MLS teams for players and coaches
CREATE TABLE playerTeams (
pid int NOT NULL,
tid int NOT NULL,
PRIMARY KEY (pid, tid),
FOREIGN KEY (pid) REFERENCES players(id) ON DELETE RESTRICT ON UPDATE CASCADE,
FOREIGN KEY (tid) REFERENCES teams(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB;

-- INSERT INTO playerTeams(pid, tid) VALUES ((SELECT id FROM players WHERE name  = ['player name']), (SELECT id FROM teams WHERE name = ['team name']));

CREATE TABLE coachTeams (
cid int NOT NULL,
tid int NOT NULL,
PRIMARY KEY (cid, tid),
FOREIGN KEY (cid) REFERENCES coaches(id) ON DELETE RESTRICT ON UPDATE CASCADE,
FOREIGN KEY (tid) REFERENCES teams(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB;

-- INSERT INTO coachTeams(cid, tid) VALUES ((SELECT id FROM coaches WHERE name  = ['coach name']), (SELECT id FROM teams WHERE name = ['team name']));

-- Code for dropping all tables if necessary
-- SET FOREIGN_KEY_CHECKS=0;
-- DROP TABLE teams, players, coaches, stadiums, playerTeams, coachTeams;
-- SET FOREIGN_KEY_CHECKS=1;

-- Code for inserting values

INSERT INTO teams(name, division, founded, joinedMLS, stadium, numUSOpenCups, numSS, numMLSCups) VALUES ([team name], [east/west], [founding date], [MLS join date], [home stadium], [US Open Cups], [Supp. Shields], [MLS Cups]);

INSERT INTO players(name, dob, team) VALUES ([Player Name], [Birthday], [Current Team]);

INSERT INTO coaches(name, dob, team) VALUES ([Coach Name], [Birthday], [Current Team]);

INSERT INTO stadiums(name, city, avgAttendance, team) VALUES ([stadium name], [city located], [average attendance last full season], [team name]);

INSERT INTO playerTeams(pid, tid) VALUES ((SELECT id FROM players WHERE name  = ['player name']), (SELECT id FROM teams WHERE name = ['team name']));

INSERT INTO coachTeams(cid, tid) VALUES ((SELECT id FROM coaches WHERE name  = ['coach name']), (SELECT id FROM teams WHERE name = ['team name']));

-- Code for more SELECTS

-- Showing all the teams in a division
SELECT name, stadium FROM teams WHERE division=[division name];

-- Showing a certain number of players
SELECT name, team FROM players ORDER BY team ASC LIMIT [how many players to view];

-- Viewing the stadiums
SELECT name, city, team FROM stadiums;

-- Viewing the coaches
SELECT name, team FROM coaches;

-- Selecting all of the former teams from a player
SELECT name FROM teams t
INNER JOIN playerTeams pt ON t.id=pt.tid
WHERE pt.pid=(SELECT id FROM players WHERE name = "Player Name");

-- Selecting all of the former teams from a player
SELECT name FROM teams t
INNER JOIN coachTeams ct ON t.id=ct.tid
WHERE ct.cid=(SELECT id FROM players WHERE name = "Coach Name");