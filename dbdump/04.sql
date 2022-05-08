-- This file: /dbdump/04.sql (UTF-8)
-- Create data for demo site
-- @requires './01.sql'
-- @requires './02.sql'
-- @requires './03.sql'

USE multisite;

INSERT INTO tblSite (ID, Title, Descr, Dir, HomeID, Login, AllowLoginYN, Passwd, Template, Lang) VALUES (1, 'Demo site', 'Multi-site CMS demo', 'demo', 1, 'demo', 1, '$2y$10$uOHgmMuTu2.eHcv/2FK1hOmA2ROthtvLtx9akty2dYsI0kcgjSBtu', 'curtain.tpl', 'en');

INSERT INTO tblContent (SiteID, Title, Data, NavBarYN) VALUES (1, 'Page 1', 'Content for page 1', 1);

INSERT INTO tblContent (SiteID, Title, Data, NavBarYN) VALUES (1, 'Page 2', 'Content for page 2', 1);

