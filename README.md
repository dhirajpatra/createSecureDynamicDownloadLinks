# createSecureDynamicDownloadLinks
Very easy to implement application to create highly secure, dynamic, download links for your application. 5 min integration with any PHP application. Download link is dynamic and secure. Folder path will be hidden so downloader not know the exact path and file name. After download link will be blocked. A specific time line for download can be set. OOPS and secured code.

Any kind of assist required inform me.

How to install:

1. Copy all files and folder to your application folder eg. /var/www/html/
2. Generate Database table by running this sql
--
-- Table structure for table `downloader`
--

DROP TABLE IF EXISTS `downloader`;
CREATE TABLE IF NOT EXISTS `downloader` (
  `id` int(11) NOT NULL,
  `secure_link` varchar(255) NOT NULL,
  `expiry` datetime NOT NULL,
  `downloaded` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `downloader`
--
ALTER TABLE `downloader`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `downloader`
--
ALTER TABLE `downloader`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

--
-- above all sql need to run in your database [you can change the name or field then you need to update the code also
--

3. You can change the downloadFolder also as per your requirement. Where you kept the file for user to download. Path will be hidden by this application. So that no body know the exact location of download file folder.
4. To create links which you can send via mail to intended user to download is
your_path/generate_link.php
5. Send the link to the user

That's all :) freely use and update as you required.
