<?php
class bmp2jpg
{
    /**
     * @convert BMP to GD
     * @param string $src
     * @param string|bool $dest
     * @return bool
     */
    public static function bmp2gd($src, $dest = false) {
        // open source file for reading
        if (!($srch = fopen($src, 'rb'))) {
            user_error('Unable to open source for reading.');
            return false;
        }

        // open the destination file for writing
        if (!($desth = fopen($dest, 'wb'))) {
            user_error('Unable to open destination for writing.');
            return false;
        }

        // get the headers
        $header = unpack('vtype/Vsize/v2reserved/Voffset', fread($srch, 14));

        // get the rest of the image
        $info = unpack('Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vimportant', fread($srch, 40));

        // extract the header and info into varibles
        extract($info);
        extract($header);

        // check for BMP signature
        if ($type != 0x4D42) {
            user_error('Source image is not a BMP.');
            return false;
        }

        // set the pallete
        $palette_size = $offset - 54;
        $ncolor = $palette_size / 4;

        // true-color vs. palette
        $gd_header = '';
        $gd_header .= ($palette_size == 0) ? "\xFF\xFE" : "\xFF\xFF";
        $gd_header .= pack('n2', $width, $height);
        $gd_header .= ($palette_size == 0) ? "\x01" : "\x00";
        if ($palette_size) {
            $gd_header .= pack('n', $ncolor);
        }

        // do not allow transparency
        $gd_header .= "\xFF\xFF\xFF\xFF";

        // write the destination headers
        fwrite($desth, $gd_header);
        unset($gd_header);

        // if we have a palette
        if ($palette_size) {
            // read the palette
            $palette = fread($srch, $palette_size);
            // begin the gd palette
            $gd_palette = '';
            $j = 0;
            // loop of the palette
            while ( $j < $palette_size ) {
                $b = $palette{$j++};
                $g = $palette{$j++};
                $r = $palette{$j++};
                $a = $palette{$j++};
                // assemble the gd palette
                $gd_palette .= $r . $g . $b . $a;
            }
            // finish the palette
            $gd_palette .= str_repeat("\x00\x00\x00\x00", 256 - $ncolor);
            // write the gd palette
            fwrite($desth, $gd_palette);
            unset($gd_palette);
        }

        // scan line size and alignment
        $scan_line_size = (($bits * $width) + 7) >> 3;
        $scan_line_align = ($scan_line_size & 0x03) ? 4 - ($scan_line_size & 0x03) : 0;

        // main loop
        for($i = 0, $l = $height - 1; $i < $height; $i++, $l--) {
            // create scan lines starting from bottom
            fseek($srch, $offset + (($scan_line_size + $scan_line_align) * $l));
            $scan_line = fread($srch, $scan_line_size);
            $gd_scan_line = '';
            if ($bits == 24) {
                $j = 0;
                while ( $j < $scan_line_size ) {
                    $b = $scan_line{$j++};
                    $g = $scan_line{$j++};
                    $r = $scan_line{$j++};
                    $gd_scan_line .= "\x00" . $r . $g . $b;
                }
            }
            else if ($bits == 8) {
                $gd_scan_line = $scan_line;
            }
            else if ($bits == 4) {
                $j = 0;
                while ( $j < $scan_line_size ) {
                    $byte = ord($scan_line{$j++});
                    $p1 = chr($byte >> 4);
                    $p2 = chr($byte & 0x0F);
                    $gd_scan_line .= $p1 . $p2;
                }
                $gd_scan_line = substr($gd_scan_line, 0, $width);
            }
            else if ($bits == 1) {
                $j = 0;
                while ( $j < $scan_line_size ) {
                    $byte = ord($scan_line{$j++});
                    $p1 = chr((int)(($byte & 0x80) != 0));
                    $p2 = chr((int)(($byte & 0x40) != 0));
                    $p3 = chr((int)(($byte & 0x20) != 0));
                    $p4 = chr((int)(($byte & 0x10) != 0));
                    $p5 = chr((int)(($byte & 0x08) != 0));
                    $p6 = chr((int)(($byte & 0x04) != 0));
                    $p7 = chr((int)(($byte & 0x02) != 0));
                    $p8 = chr((int)(($byte & 0x01) != 0));
                    $gd_scan_line .= $p1 . $p2 . $p3 . $p4 . $p5 . $p6 . $p7 . $p8;
                }
                // put the gd scan lines together
                $gd_scan_line = substr($gd_scan_line, 0, $width);
            }
            // write the gd scan lines
            fwrite($desth, $gd_scan_line);
            unset($gd_scan_line);
        }
        // close the source file
        fclose($srch);
        // close the destination file
        fclose($desth);
        // return destination file
        return $dest;
    }

    /**
     * @ceate a BMP image
     * @param string $filename
     * @return bin string on success
     * @return bool false on failure
     */
    public static function imagecreatefrombmp($filename) {
        // create a temp file
        $tmpfile = tempnam(sys_get_temp_dir(), 'gd');
        // convert to gd
        if (bmp2gd($filename, $tmpfile)) {
            // create image resource
            $img = imagecreatefromgd($tmpfile);
        }
        // remove temp file
        @unlink($tmpfile);
        return isset($img) ? $img : false;
    }
}
?>