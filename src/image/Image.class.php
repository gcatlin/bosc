<?php
//  ___________________________________________________________________________
// /                                                                           \
// |  Bosc                                                                     |
// |                                                                           |
// |      Bosc is a PHP class library for writing web applications             |
// |      http://bosc-project.org/                                             |
// |                                                                           |
// |  Copyright (c) 2004  Geoff Catlin <geoff@bosc-project.org>                |
// |  ________________________________________________________________________ |
// |                                                                           |
// |  This library is free software; you can redistribute it and or            |
// |  modify it under the terms of the GNU Lesser General Public               |
// |  License as published by the Free Software Foundation; either             |
// |  version 2.1 of the License, or (at your option) any later version.       |
// |                                                                           |
// |  This library is distributed in the hope that it will be useful,          |
// |  but WITHOUT ANY WARRANTY; without even the implied warranty of           |
// |  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU        |
// |  Lesser General Public License for more details.                          |
// |                                                                           |
// |  You should have received a copy of the GNU Lesser General Public         |
// |  License along with this library; if not, write to the Free Software      |
// |  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA  |
// \___________________________________________________________________________/

/**
 * @package    bosc
 * @subpackage image
 */
if (! defined('BOSC'))
    require_once(realpath(dirname(__FILE__).'/../bosc.inc.php'));

/**
 * Resources:
 *   o http://pear.php.net/packages.php?catpid=12&catname=Images
 *   o http://phpclasses.iplexx.at/browse.html/class/11.html
 */
class Image extends Object
{

    var $drawBrush;
    var $drawColor;
    var $drawStyle;
    var $drawThickness;
    var $height;
    var $im;
    var $textColor;
    var $textFont;
    var $textSize;
    var $width;

    function Image()
    {
        switch (func_num_args())
        {
            case 2:
                $width  = func_get_arg(0);
                $height = func_get_arg(1);
                $this->_setImage($this->create($width, $height));
                break;
            case 1:
            default:
                $filename = func_get_arg(0);
                $this->_setImage($this->createFromFile($filename));
                break;
        }
        $this->drawThickness = 1;
    }

    function constrainProportions($dstW, $dstH, $srcW, $srcH)
    {
        $dstH = max(1, $dstH);
        $srcH = max(1, $srcH);
        if ($dstW != $srcW || $dstH != $srcH)
        {
            $dstRatio = $dstW / $dstH;
            $srcRatio = $srcW / $srcH;
            if ($dstRatio < $srcRatio)
                $dstH = (int) floor($dstW / $srcRatio);
            elseif ($srcRatio < $dstRatio)
                $dstW = (int) floor($dstH * $srcRatio);
        }
        return array($dstW, $dstH);
    }

    function copy($dstX, $dstY, $srcX, $srcY, $srcW, $srcH)
    {
        $tmp = $this->create($srcW, $srcH);
        @imagecopy($tmp, $this->im, 0, 0, $srcX, $srcY, $srcW, $srcH);
        @imagecopy($this->im, $tmp, $dstX, $dstY, 0, 0, $srcW, $srcH);
        @imagedestroy($tmp);
    }

    function copyResized($dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH)
    {
        $tmp = $this->create($srcW, $srcH);
        @imagecopy($tmp, $this->im, 0, 0, $srcX, $srcY, $srcW, $srcH);
        $this->_imageCopyResampled($this->im, $tmp, $dstX, $dstY, 0, 0, $dstW, $dstH, $srcW, $srcH);
        @imagedestroy($tmp);
    }

    function create($width, $height)
    {
        return;
    }

    function createFromFile($filename)
    {
        return;
    }

    function crop($srcX, $srcY, $srcW, $srcH)
    {
        $tmp = $this->create($srcW, $srcH);
        @imagecopy($tmp, $this->im, 0, 0, $srcX, $srcY, $srcW, $srcH);
        $this->_setImage($tmp);
    }

    function cropRandom($srcW, $srcH)
    {
        $srcX = mt_rand(0, max(0, $this->width - $srcW));
        $srcY = mt_rand(0, max(0, $this->height - $srcH));
        $this->crop($srcX, $srcY, $srcW, $srcH);
    }

    function destroy()
    {
        @imagedestroy($this->im);
        $this->im = NULL;
    }

    function diplay()
    {
        return;
    }

    function drawArc($cx, $cy, $w, $h, $s, $e)
    {
        @imagearc($this->im, $cx, $cy, $w, $h, $s, $e, $this->drawColor);
    }

    function drawCircle($cx, $cy, $r)
    {
        $this->drawEllipse($cx, $cy, $r << 1, $r << 1);
    }

    function drawBorder()
    {
        $offset1 = (int) floor($this->drawThickness / 2);
        $offset2 = (int) ceil($this->drawThickness / 2);
        $x1 = $offset1;
        $y1 = $offset1;
        $x2 = $this->width - $offset2;
        $y2 = $this->height - $offset2;
        $this->drawRectangle($x1, $y1, $x2, $y2);
    }

    function drawDashedLine($x1, $y1, $x2, $y2)
    {
        $this->setDrawStyle();
        $this->drawLine($x1, $y1, $x2, $y2);
    }

    function drawEllipse($cx, $cy, $w, $h)
    {
        //@imageellipse($this->im, $cx, $cy, $w, $h, $this->drawColor);
        $this->drawArc($cx, $cy, $w, $h, 0, 359);
    }

    function drawFilledArc($cx, $cy, $w, $h, $s, $e)
    {
        @imagefilledarc($this->im, $cx, $cy, $w, $h, $s, $e, $this->drawColor, $this->drawStyle);
    }

    function drawFilledEllipse($cx, $cy, $w, $h)
    {
        //@imagefilledellipse($this->im, $cx, $cy, $w, $h, $this->drawColor);
        $this->drawFilledArc($cx, $cy, $w, $h, 0, 359);
    }

    function drawFilledPolygon($points)
    {
        @imagefilledpolygon($this->im, $points, count($points) >> 1, $this->drawColor);
    }

    function drawFilledRectangle($x1, $y1, $x2, $y2)
    {
        @imagefilledrectangle($this->im, $x1, $y1, $x2, $y2, $this->drawColor);
    }

    function drawLine($x1, $y1, $x2, $y2)
    {
        @imageline($this->im, $x1, $y1, $x2, $y2, $this->drawColor);
    }

    function drawPolygon($points)
    {
        @imagepolygon($this->im, $points, count($points) >> 1, $this->drawColor);
    }

    function drawRectangle($x1, $y1, $x2, $y2)
    {
        @imagerectangle($this->im, $x1, $y1, $x2, $y2, $this->drawColor);
    }

    function drawText($text, $dstX=0, $dstY=0, $angle=0)
    {
        list($x1, $y1, $x2, $y2, $x3, $y3, $x4, $y4) = @imagettfbbox($this->textSize, $angle, $this->textFont, $text);
        $dstX -= min($x1, $x2, $x3, $x4);
        $dstY -= min($y1, $y2, $y3, $y4);
        return @imagettftext($this->im, $this->textSize, $angle, $dstX, $dstY, $this->textColor, $this->textFont, $text);
    }

    function getHeight()
    {
        return $this->height;
    }

    function getImage()
    {
        return $this->im;
    }

    function getWidth()
    {
        return $this->width;
    }

    function _imageCopyResampled($dstIm, $srcIm, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH)
    {
        if (in_array('imagecopyresampled', get_extension_funcs('gd')) && ! @imagecolorstotal($srcIm))
        {
            @imagecopyresampled($dstIm, $srcIm, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
        }
        else
        {
            @imagepalettecopy($dstIm, $srcIm);

            $colors = array();

            $scaleX   = ($srcW - ($srcW % 2)) / $dstW;
            $scaleY   = ($srcH - ($srcH % 2)) / $dstH;

            $scaleX_2 = (int) $scaleX / 1.5;//round($scaleX * 0.5);
            $scaleY_2 = (int) $scaleY / 1.5;//round($scaleY * 0.5);

            $dstImW = @imagesx($dstIm);
            $dstImH = @imagesy($dstIm);
            $srcImW = @imagesx($srcIm);
            $srcImH = @imagesy($srcIm);

            for ($j = 0; $j < $dstH; $j++)
            {
                $sY  = (int) ($j * $scaleY) + $srcY;
                $dY  = $j + $dstY;
                $y13 = $sY + $scaleY_2;
                if ($sY > $srcImH || $dY > $dstImH)
                {
                    break;
                }

                for ($i = 0; $i < $dstW; $i++)
                {
                    $sX  = (int) ($i * $scaleX) + $srcX;
                    $dX  = $i + $dstX;
                    $x34 = $sX + $scaleX_2;
                    if ($sX > $srcImW || $dX > $dstImW)
                        break;

                    $index1 = @imagecolorat($srcIm, $sX,  $y13);
                    $index2 = @imagecolorat($srcIm, $sX,  $sY);
                    $index3 = @imagecolorat($srcIm, $x34, $y13);
                    $index4 = @imagecolorat($srcIm, $x34, $sY);

                    if (! $colors[$index1])
                        $colors[$index1] = @imagecolorsforindex($srcIm, $index1);
                    if (! $colors[$index2])
                        $colors[$index2] = @imagecolorsforindex($srcIm, $index2);
                    if (! $colors[$index3])
                        $colors[$index3] = @imagecolorsforindex($srcIm, $index3);
                    if (! $colors[$index4])
                        $colors[$index4] = @imagecolorsforindex($srcIm, $index4);

                    $c1 = $colors[$index1];
                    $c2 = $colors[$index2];
                    $c3 = $colors[$index3];
                    $c4 = $colors[$index4];

                    $r = ($c1['red']   + $c2['red']   + $c3['red']   + $c4['red'])   / 4;
                    $g = ($c1['green'] + $c2['green'] + $c3['green'] + $c4['green']) / 4;
                    $b = ($c1['blue']  + $c2['blue']  + $c3['blue']  + $c4['blue'])  / 4;

                    @imagesetpixel($dstIm, $dX, $dY, @imagecolorclosest($dstIm, $r, $g, $b));
                }
            }
        }
    }

    function isImage()
    {
        return (is_resource($this->im) && get_resource_type($this->im) == 'gd');
    }

    function merge(&$srcIm, $dstX, $dstY, $opacity=100)
    {
        @imagecopymerge($this->im, $srcIm->getImage(), $dstX, $dstY, 0, 0, $srcIm->getWidth(), $srcIm->getHeight(), $opacity);
    }

    function offset($x=0, $y=0, $wrap=FALSE)
    {
        $x = (int) $x;
        $y = (int) $y;
        $tmp = $this->create($this->width, $this->height);
        @imagecopy($tmp, $this->im, $x, $y, 0, 0, $this->width - $x, $this->height - $y);
        if ($wrap)
        {
            @imagecopy($tmp, $this->im, 0, 0, $this->width - $x, $this->height - $y, $x, $y);
            @imagecopy($tmp, $this->im, $x, 0, 0, $this->height - $y, $this->width - $x, $y);
            @imagecopy($tmp, $this->im, 0, $y, $this->width - $x, 0, $x, $this->height - $y);
        }
        $this->_setImage($tmp);
    }

    function output()
    {
        return;
    }

    function resize($dstW, $dstH)
    {
        $tmp = $this->create($dstW, $dstH);
        $this->_imageCopyResampled($tmp, $this->im, 0, 0, 0, 0, $dstW, $dstH, $this->width, $this->height);
        $this->_setImage($tmp);
    }

    function rotate($angle=0, $r=0, $g=0, $b=0)
    {
        @imagerotate($this->im, $angle, imagecolorexact($this->im, $r, $g, $b));
    }

    function scale()
    {
        switch (func_num_args())
        {
            case 2:
                $maxW = func_get_arg(0);
                $maxH = func_get_arg(1);
                list($dstW, $dstH) = $this->constrainProportions($maxW, $maxH, $this->width, $this->height);
                break;
            case 1:
            default:
                $pct = func_get_arg(0);
                $dstW = round($pct * $this->width);
                $dstH = round($pct * $this->height);
                break;
        }
        $this->resize($dstW, $dstH);
    }

    function setAlphaBlending($blendmode=TRUE)
    {
        @imagealphablending($this->im, $blendmode);
    }

    function setDrawBrush(&$image)
    {
        @imagesetbrush($this->im, $image);
    }

    function setDrawColor($r, $g, $b)
    {
        $this->drawColor = @imagecolorexact($this->im, $r, $g, $b);
    }

    function setDrawStyle($style)
    {
        @imagesetstyle($this->im, $style);
    }

    function setDrawThickness($thickness)
    {
        @imagesetthickness($this->im, $thickness);
        $this->drawThickness = (int) $thickness;
    }

    function _setImage($im)
    {
        if (is_resource($im) && get_resource_type($im) == 'gd')
        {
            if ($this->im != $im)
                $this->destroy();
            $this->im = $im;
            $this->width = @imagesx($this->im);
            $this->height = @imagesy($this->im);
        }
    }

    function setTextColor($r, $g, $b, $alpha=100)
    {
        $this->textColor = ($alpha == 100 ?
            imagecolorexact($this->im, $r, $g, $b) :
            imagecolorexactalpha($this->im, $r, $g, $b, (100 - $alpha) * 1.27));
    }

    function setTextFont($file)
    {
        $this->textFont = $file;
    }

    function setTextSize($size)
    {
        $this->textSize = $size;
    }

    function thumbnail($dstW, $dstH, $fill_color=array(255, 0, 0))
    {
        list($r, $g, $b) = $fill_color;
        $this->scale($dstW, $dstH);
        list($srcW, $srcH) = array($this->width, $this->height);
        $max  = max($srcW, $srcH);
        $tmp = $this->create($max, $max);
        $dstX = abs($max - $srcW) >> 1;
        $dstY = abs($max - $srcH) >> 1;
        @imagefilledrectangle($tmp, 0, 0, $max, $max, @imagecolorexact($r, $g, $b));
        @imagecopymerge($tmp, $this->im, $dstX, $dstY, 0, 0, $srcW, $srcH, 100);
        $this->_setImage($tmp);
    }

    function thumbnailCropped($dstW, $dstH)
    {
        list($srcW, $srcH) = $this->constrainProportions($this->width, $this->height, $dstW, $dstH);
        $this->crop($srcX, $srcY, $srcW, $srcH);
        $this->resize($dstW, $dstH);
    }

    function writeToFile($filename, $quality=75)
    {
        ob_start();
        $this->output($quality);
        $output = ob_get_clean();
        $fp = @fopen($filename, 'wb');
        @fwrite($fp, $output);
        @fclose($fp);
    }

}

?>
