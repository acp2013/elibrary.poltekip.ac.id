    break;

        case '':
        case 'title':   // Retained for backwards compatibility?
            break;

        default:
            $this->CheckOption($type, 'data, time, printf, custom', __FUNCTION__);
            $type = '';
        }
        $format['type'] = $type;
        return (boolean)$type;
    }


    /*
     * Select label formating for X tick labels, and for X data labels
     * (unless SetXDataLabelType was called).
     * See SetLabelType() for details.
     */
    function SetXLabelType()  // Variable arguments: $type, ...
    {
        $args = func_get_args();
        return $this->SetLabelType('x', $args);
    }

    /*
     * Select label formatting for X data labels, overriding SetXLabelType.
     */
    function SetXDataLabelType()  // Variable arguments: $type, ...
    {
        $args = func_get_args();
        return $this->SetLabelType('xd', $args);
    }

    /*
     * Select label formating for Y tick labels, and for Y data labels
     * (unless SetYDataLabelType was called).
     * See SetLabelType() for details.
     */
    function SetYLabelType()  // Variable arguments: $type, ...
    {
        $args = func_get_args();
        return $this->SetLabelType('y', $args);
    }

    /*
     * Select label formatting for Y data labels, overriding SetYLabelType.
     */
    function SetYDataLabelType()  // Variable arguments: $type, ...
    {
        $args = func_get_args();
        return $this->SetLabelType('yd', $args);
    }

    function SetXTimeFormat($which_xtf)
    {
        $this->label_format['x']['time_format'] = $which_xtf;
        return TRUE;
    }

    function SetYTimeFormat($which_ytf)
    {
        $this->label_format['y']['time_format'] = $which_ytf;
        return TRUE;
    }

    function SetNumberFormat($decimal_point, $thousands_sep)
    {
        $this->decimal_point = $decimal_point;
        $this->thousands_sep = $thousands_sep;
        return TRUE;
    }


    function SetXLabelAngle($which_xla)
    {
        $this->x_label_angle = $which_xla;
        return TRUE;
    }

    function SetYLabelAngle($which_yla)
    {
        $this->y_label_angle = $which_yla;
        return TRUE;
    }

    // If used, this sets the angle for X Data Labels only, separately from tick labels.
    function SetXDataLabelAngle($which_xdla)
    {
        $this->x_data_label_angle = $which_xdla;
        return TRUE;
    }

    // Sets the angle for Y Data Labels. Unlike X Data Labels, these default to zero.
    function SetYDataLabelAngle($which_ydla)
    {
        $this->y_data_label_angle = $which_ydla;
        return TRUE;
    }


/////////////////////////////////////////////
///////////                              MISC
/////////////////////////////////////////////

    /*!
     * Checks the validity of an option.
     *   $which_opt  String to check, such as the provided value of a function argument.
     *   $which_acc  String of accepted choices. Must be lower-case, and separated
     *               by exactly ', ' (comma, space).
     *   $which_func Name of the calling function, for error messages.
     * Returns the supplied option value, downcased and trimmed, if it is valid.
     * Reports an error if the supplied option is not valid.
     */
    protected function CheckOption($which_opt, $which_acc, $which_func)
    {
        $asked = strtolower(trim($which_opt));

        # Look for the supplied value in a comma/space separated list.
        if (strpos(", $which_acc,", ", $asked,") !== False)
            return $asked;

        $this->PrintError("$which_func(): '$which_opt' not in available choices: '$which_acc'.");
        return NULL;
    }


    /*!
     *  \note Submitted by Thiemo Nagel
     */
    function SetBrowserCache($which_browser_cache)
    {
        $this->browser_cache = $which_browser_cache;
        return TRUE;
    }

    /*!
     * Whether to show the final image or not
     */
    function SetPrintImage($which_pi)
    {
        $this->print_image = $which_pi;
        return TRUE;
    }

    /*!
     * Sets the graph's legend. If argument is not an array, appends it to the legend.
     */
    function SetLegend($which_leg)
    {
        if (is_array($which_leg)) {             // use array
            $this->legend = $which_leg;
        } elseif (! is_null($which_leg)) {     // append string
            $this->legend[] = $which_leg;
        } else {
            return $this->PrintError("SetLegend(): argument must not be null.");
        }
        return TRUE;
    }

    /*!
     * Specifies the position of the legend's upper/leftmost corner,
     * in pixel (device) coordinates.
     */
    function SetLegendPixels($which_x, $which_y)
    {
        $this->legend_x_pos = $which_x;
        $this->legend_y_pos = $which_y;
        // Make sure this is unset, meaning we have pixel coords:
        unset($this->legend_xy_world);

        return TRUE;
    }

    /*!
     * Specifies the position of the legend's upper/leftmost corner,
     * in world (data space) coordinates.
     * Since the scale factor to convert world to pixel coordinates
     * is probably not available, set a flag and defer conversion
     * to later.
     */
    function SetLegendWorld($which_x, $which_y)
    {
        $this->legend_x_pos = $which_x;
        $this->legend_y_pos = $which_y;
        $this->legend_xy_world = True;

        return TRUE;
    }

    /*
     * Set legend text alignment, color box alignment, and style options
     *     $text_align accepts 'left' or 'right'.
     *     $colorbox_align accepts 'left', 'right', 'none', or missing/empty. If missing or empty,
     *        the same alignment as $text_align is used. Color box is positioned first.
     *     $style is reserved for future use.
     */
    function SetLegendStyle($text_align, $colorbox_align = '', $style = '')
    {
        $this->legend_text_align = $this->CheckOption($text_align, 'left, right', __FUNCTION__);
        if (empty($colorbox_align))
            $this->legend_colorbox_align = $this->legend_text_align;
        else
            $this->legend_colorbox_align = $this->CheckOption($colorbox_align, 'left, right, none', __FUNCTION__);
        return ((boolean)$this->legend_text_align && (boolean)$this->legend_colorbox_align);
    }

    /*!
     * Accepted values are: left, sides, none, full
     */
    function SetPlotBorderType($pbt)
    {
        $this->plot_border_type = $this->CheckOption($pbt, 'left, sides, none, full', __FUNCTION__);
        return (boolean)$this->plot_border_type;
    }

    /*!
     * Accepted values are: raised, plain
     */
    function SetImageBorderType($sibt)
    {
        $this->image_border_type = $this->CheckOption($sibt, 'raised, plain, none', __FUNCTION__);
        return (boolean)$this->image_border_type;
    }


    /*!
     * \param dpab bool
     */
    function SetDrawPlotAreaBackground($dpab)
    {
        $this->draw_plot_area_background = (bool)$dpab;
        return TRUE;
    }


    /*!
     * \param dyg bool
     */
    function SetDrawYGrid($dyg)
    {
        $this->draw_y_grid = (bool)$dyg;
        return TRUE;
    }


    /*!
     * \param dxg bool
     */
    function SetDrawXGrid($dxg)
    {
        $this->draw_x_grid = (bool)$dxg;
        return TRUE;
    }


    /*!
     * \param ddg bool
     */
    function SetDrawDashedGrid($ddg)
    {
        $this->dashed_grid = (bool)$ddg;
        return TRUE;
    }


    /*!
     * \param dxdl bool
     */
    function SetDrawXDataLabelLines($dxdl)
    {
        $this->draw_x_data_label_lines = (bool)$dxdl;
        return TRUE;
    }

    /*!
     * Sets the graph's title.
     * TODO: add parameter to choose title placement: left, right, centered=
     */
    function SetTitle($which_title)
    {
        $this->title_txt = $which_title;
        return TRUE;
    }

    /*!
     * Sets the X axis title and position.
     */
    function SetXTitle($which_xtitle, $which_xpos = 'plotdown')
    {
        if ($which_xtitle == '')
            $which_xpos = 'none';

        $this->x_title_pos = $this->CheckOption($which_xpos, 'plotdown, plotup, both, none', __FUNCTION__);
        if (!$this->x_title_pos) return FALSE;
        $this->x_title_txt = $which_xtitle;
        return TRUE;
    }


    /*!
     * Sets the Y axis title and position.
     */
    function SetYTitle($which_ytitle, $which_ypos = 'plotleft')
    {
        if ($which_ytitle == '')
            $which_ypos = 'none';

        $this->y_title_pos = $this->CheckOption($which_ypos, 'plotleft, plotright, both, none', __FUNCTION__);
        if (!$this->y_title_pos) return FALSE;
        $this->y_title_txt = $which_ytitle;
        return TRUE;
    }

    /*!
     * Sets the size of the drop shadow for bar and pie charts.
     * \param which_s int Size in pixels.
     */
    function SetShading($which_s)
    {
        $this->shading = (int)$which_s;
        return TRUE;
    }

    function SetPlotType($which_pt)
    {
        $this->plot_type = $this->CheckOption($which_pt,
                           'bars, stackedbars, lines, linepoints, area, points, pie, thinbarline, squared',
                            __FUNCTION__);
        return (boolean)$this->plot_type;
    }

    /*!
     * Sets the position of Y axis.
     * \param pos int Position in world coordinates.
     */
    function SetYAxisPosition($pos)
    {
        $this->y_axis_position = (int)$pos;
        return TRUE;
    }

    /*!
     * Sets the position of X axis.
     * \param pos int Position in world coordinates.
     */
    function SetXAxisPosition($pos)
    {
        $this->x_axis_position = (int)$pos;
        return TRUE;
    }


    function SetXScaleType($which_xst)
    {
        $this->xscale_type = $this->CheckOption($which_xst, 'linear, log', __FUNCTION__);
        return (boolean)$this->xscale_type;
    }

    function SetYScaleType($which_yst)
    {
        $this->yscale_type = $this->CheckOption($which_yst, 'linear, log',  __FUNCTION__);
        return (boolean)$this->yscale_type;
    }

    function SetPrecisionX($which_prec)
    {
        return $this->SetXLabelType('data', $which_prec);
    }

    function SetPrecisionY($which_prec)
    {
        return $this->SetYLabelType('data', $which_prec);
    }

    function SetErrorBarLineWidth($which_seblw)
    {
        $this->error_bar_line_width = $which_seblw;
        return TRUE;
    }

    function SetLabelScalePosition($which_blp)
    {
        //0 to 1
        $this->label_scale_position = $which_blp;
        return TRUE;
    }

    function SetErrorBarSize($which_ebs)
    {
        //in pixels
        $this->error_bar_size = $which_ebs;
        return TRUE;
    }

    /*!
     * Can be one of: 'tee', 'line'
     */
    function SetErrorBarShape($which_ebs)
    {
        $this->error_bar_shape = $this->CheckOption($which_ebs, 'tee, line', __FUNCTION__);
        return (boolean)$this->error_bar_shape;
    }

    /*
     * Synchronize the point shape and point size arrays.
     * This is called just before drawing any plot that needs 'points'.
     */
    protected function CheckPointParams()
    {
        // Make both point_shapes and point_sizes the same size, by padding the smaller.
        $ps = count($this->point_sizes);
        $pt = count($this->point_shapes);

        if ($ps < $pt) {
            $this->pad_array($this->point_sizes, $pt);
            $this->point_counts = $pt;
        } else if ($ps > $pt) {
            $this->pad_array($this->point_shapes, $ps);
            $this->point_counts = $ps;
        }

        // Note: PHPlot used to check and adjust point_sizes to be an even number here,
        // for all 'diamond' and 'triangle' shapes. The reason for this having been
        // lost, and the current maintainer seeing no sense it doing this for only
        // some shapes, the code has been removed. But see what DrawDot() does.
    }

    /*!
     * Sets point shape for each data set via an array.
     * For a list of valid shapes, see the CheckOption call below.
     * The point shape and point sizes arrays are synchronized before drawing a graph
     * that uses points. See CheckPointParams()
     */
    function SetPointShapes($which_pt)
    {
        if (is_array($which_pt)) {
            // Use provided array:
            $this->point_shapes = $which_pt;
        } elseif (!is_null($which_pt)) {
            // Make the single value into an array:
            $this->point_shapes = array($which_pt);
        }

        // Validate all the shapes. This list must agree with DrawDot().
        foreach ($this->point_shapes as $shape)
        {
            if (!$this->CheckOption($shape, 'halfline, line, plus, cross, rect, circle, dot,'
                       . ' diamond, triangle, trianglemid, delta, yield, star, hourglass,'
                       . ' bowtie, target, box, home, up, down, none', __FUNCTION__))
                return FALSE;
        }
        return TRUE;
    }

    /*!
     * Sets the point size for point plots.
     * The point shape and point sizes arrays are synchronized before drawing a graph
     * that uses points. See CheckPointParams()
     */
    function SetPointSizes($which_ps)
    {
        if (is_array($which_ps)) {
            // Use provided array:
            $this->point_sizes = $which_ps;
        } elseif (!is_null($which_ps)) {
            // Make the single value into an array:
            $this->point_sizes = array($which_ps);
        }
        return TRUE;
    }

    /*!
     * Tells not to draw lines for missing Y data. Only works with 'lines' and 'squared' plots.
     * \param bl bool
     */
    function SetDrawBrokenLines($bl)
    {
        $this->draw_broken_lines = (bool)$bl;
        return TRUE;
    }


    /*!
     *  text-data: ('label', y1, y2, y3, ...)
     *  text-data-single: ('label', data), for some pie charts.
     *  data-data: ('label', x, y1, y2, y3, ...)
     *  data-data-error: ('label', x1, y1, e1+, e2-, y2, e2+, e2-, y3, e3+, e3-, ...)
     */
    function SetDataType($which_dt)
    {
        //The next four lines are for past compatibility.
        if ($which_dt == 'text-linear') { $which_dt = 'text-data'; }
        elseif ($which_dt == 'linear-linear') { $which_dt = 'data-data'; }
        elseif ($which_dt == 'linear-linear-error') { $which_dt = 'data-data-error'; }
        elseif ($which_dt == 'text-data-pie') { $which_dt = 'text-data-single'; }


        $this->data_type = $this->CheckOption($which_dt, 'text-data, text-data-single, '.
                                                         'data-data, data-data-error', __FUNCTION__);
        return (boolean)$this->data_type;
    }

    /*!
     * Copy the array passed as data values. We convert to numerical indexes, for its
     * use for (or while) loops, which sometimes are faster. Performance improvements
     * vary from 28% in DrawLines() to 49% in DrawArea() for plot drawing functions.
     */
    function SetDataValues(&$which_dv)
    {
        $this->num_data_rows = count($which_dv);
        $this->total_records = 0;               // Perform some useful calculations.
        $this->records_per_group = 1;
        for ($i = 0, $recs = 0; $i < $this->num_data_rows; $i++) {
            // Copy
            $this->data[$i] = array_values($which_dv[$i]);   // convert to numerical indices.

            // Compute some values
            $recs = count($this->data[$i]);
            $this->total_records += $recs;

            if ($recs > $this->records_per_group)
                $this->records_per_group = $recs;

            $this->num_recs[$i] = $recs;
        }
        return TRUE;
    }

    /*!
     * Pad styles arrays for later use by plot drawing functions:
     * This removes the need for $max_data_colors, etc. and $color_index = $color_index % $max_data_colors
     * in DrawBars(), DrawLines(), etc.
     */
    protected function PadArrays()
    {
        $this->pad_array($this->line_widths, $this->records_per_group);
        $this->pad_array($this->line_styles, $this->records_per_group);

        $this->pad_array($this->data_colors, $this->records_per_group);
        $this->pad_array($this->data_border_colors, $this->records_per_group);
        $this->pad_array($this->error_bar_colors, $this->records_per_group);

        $this->SetDataColors();
        $this->SetDataBorderColors();
        $this->SetErrorBarColors();

        return TRUE;
    }

    /*!
     * Pads an array with itself. This only works on 0-based sequential integer indexed arrays.
     *  \param arr array  Original array (reference), or scalar.
     *  \param size int   Minimum size of the resulting array.
     * If $arr is a scalar, it will be converted first to a single element array.
     * If $arr has at least $size elements, it is unchanged.
     * Otherwise, append elements of $arr to itself until it reaches $size elements.
     */
    protected function pad_array(&$arr, $size)
    {
        if (! is_array($arr)) {
            $arr = array($arr);
        }
        $n = count($arr);
        $base = 0;
        while ($n < $size) $arr[$n++] = $arr[$base++];
    }

    /*
     * Format a floating-point number.
     * This is like PHP's number_format, but uses class variables for separators.
     * The separators will default to locale-specific values, if available.
     * Note: This method should be 'protected', but is called from test script(s).
     */
    function number_format($number, $decimals=0)
    {
        if (!isset($this->decimal_point) || !isset($this->thousands_sep)) {
            // Load locale-specific values from environment, unless disabled:
            if (empty($this->locale_override))
                @setlocale(LC_ALL, '');
            // Fetch locale settings:
            $locale = @localeconv();
            if (!empty($locale) && isset($locale['decimal_point']) &&
                    isset($locale['thousands_sep'])) {
              $this->decimal_point = $locale['decimal_point'];
              $this->thousands_sep = $locale['thousands_sep'];
            } else {
              // Locale information not available.
              $this->decimal_point = '.';
              $this->thousands_sep = ',';
            }
        }
        return number_format($number, $decimals, $this->decimal_point, $this->thousands_sep);
    }

    /*
     * Register a callback (hook) function
     *   reason - A pre-defined name where a callback can be defined.
     *   function - The name of a function to register for callback, or an instance/method
     *      pair in an array (see 'callbacks' in the PHP reference manual).
     *   arg - Optional argument to supply to the callback function when it is triggered.
     *      (Often called "clientData")
     * Returns: True if the callback reason is valid, else False.
     */
    function SetCallback($reason, $function, $arg = NULL)
    {
        // Use array_key_exists because valid reason keys have NULL as value.
        if (!array_key_exists($reason, $this->callbacks))
            return False;
        $this->callbacks[$reason] = array($function, $arg);
        return True;
    }

    /*
     * Return the name of a function registered for callback. See SetCallBack.
     *   reason - A pre-defined name where a callback can be defined.
     * Returns the current callback function (name or array) for the given reason,
     * or False if there was no active callback or the reason is not valid.
     * Note you can safely test the return value with a simple 'if', as
     * no valid function name evaluates to false.
     */
    function GetCallback($reason)
    {
        if (isset($this->callbacks[$reason]))
            return $this->callbacks[$reason][0];
        return False;
    }

    /*
     * Un-register (remove) a function registered for callback.
     *   reason - A pre-defined name where a callback can be defined.
     * Returns: True if it was a valid callback reason, else False.
     * Note: Returns True whether or not there was a callback registered.
     */
    function RemoveCallback($reason)
    {
        if (!array_key_exists($reason, $this->callbacks))
            return False;
        $this->callbacks[$reason] = NULL;
        return True;
    }

    /*
     * Invoke a callback, if one is registered.
     * Accepts a variable number of arguments >= 1:
     *    reason : A string naming the callback.
     *    ... : Zero or more additional arguments to be passed to the
     *      callback function, after the passthru argument:
     *           callback_function($image, $passthru, ...)
     * Returns: nothing.
     */
    protected function DoCallback()  # Note: Variable arguments
    {
        $args = func_get_args();
        $reason = $args[0];
        if (!isset($this->callbacks[$reason]))
            return;
        list($function, $args[0]) = $this->callbacks[$reason];
        array_unshift($args, $this->img);
        # Now args[] looks like: img, passthru, extra args...
        call_user_func_array($function, $args);
    }


//////////////////////////////////////////////////////////
///////////         DATA ANALYSIS, SCALING AND TRANSLATION
//////////////////////////////////////////////////////////

    /*!
     * Analyzes data and sets up internal maxima and minima
     * Needed by: CalcMargins(), ...
     * Data type text-data has: title, Y1, Y2, ... (with X implied)
     * Data type data-data has: title, X, Y1, Y2, ...
     * Data type data-data-error: has title, X, Y1, Y1err+, Y1err-, Y2, Y2err+, Y2err-, ...
     * Plot type 'stackedbars' is a special case because the bars always start at 0, and the
     *    Y values in each row accumulate.
     * Note: This method should be 'protected', but is called from test script(s).
     */
    function FindDataLimits()
    {
        # Determine how to process the data array:
        $process_x = ($this->data_type == 'data-data' || $this->data_type == 'data-data-error');
        $process_err_bars = ($this->data_type == 'data-data-error');
        $process_stacked_bars = ($this->plot_type == 'stackedbars');

        # These need to be initialized in case there are multiple plots and
        # missing data points.
        $this->data_miny = array();
        $this->data_maxy = array();

        # X values are in the data array or assumed?
        if ($process_x) {
            $all_x = array();
        } else {
            $all_x = array(0, $this->num_data_rows - 1);
        }

        # Process all rows of data:
        for ($i = 0; $i < $this->num_data_rows; $i++) {
            $n_vals = $this->num_recs[$i];
            $j = 1; # Skips label at [0]

            if ($process_x) {
                $all_x[] = (double)$this->data[$i][$j++];
            }

            if ($process_stacked_bars) {
                $all_y = array(0, 0); # Min (always 0) and max
            } else {
                $all_y = array();
            }
            while ($j < $n_vals) {
                if (is_numeric($this->data[$i][$j])) {
                    $val = (double)$this->data[$i][$j++];

                    if ($process_err_bars) {
                        $all_y[] = $val + (double)$this->data[$i][$j++];
                        $all_y[] = $val - (double)$this->data[$i][$j++];
                    } elseif ($process_stacked_bars) {
                        $all_y[1] += $val;
                    } else {
                        $all_y[] = $val;
                    }
                } else {    # Missing Y value
                  $j++;
                  if ($process_err_bars) $j += 2;
                }
            }
            if (!empty($all_y)) {
                $this->data_miny[$i] = min($all_y);  # Store per-row Y range
                $this->data_maxy[$i] = max($all_y);
            }
        }

        $this->min_x = min($all_x);  # Store X range
        $this->max_x = max($all_x);
        if (empty($this->data_miny)) { # Guard against regressive case: No Y at all
            $this->min_y = 0;
            $this->max_y = 0;
        } else {
            $this->min_y = min($this->data_miny);  # Store global Y range
            $this->max_y = max($this->data_maxy);
        }

        if ($this->GetCallback('debug_scale')) {
            $this->DoCallback('debug_scale', __FUNCTION__, array(
                'min_x' => $this->min_x, 'min_y' => $this->min_y,
                'max_x' => $this->max_x, 'max_y' => $this->max_y));
        }
        return TRUE;
    }

    /*!
     * Calculates image margins on the fly from title positions and sizes,
     * and tick labels positions and sizes.
     *
     * A picture of the locations of elements and spacing can be found in the
     * PHPlot Reference Manual.
     *
     * Calculates the following (class variables unless noted):
     *
     * Plot area margins (see note below):
     *     y_top_margin
     *     y_bot_margin
     *     x_left_margin
     *     x_right_margin
     *
     * Title sizes (these are now local, not class variables, since they are not used elsewhere):
     *     title_height : Height of main title
     *     x_title_height : Height of X axis title, 0 if no X title
     *     y_title_width : Width of Y axis title, 0 if no Y title
     *
     * Tick/Data label offsets, relative to plot_area:
     *     x_label_top_offset, x_label_bot_offset, x_label_axis_offset
     *     y_label_left_offset, y_label_right_offset, y_label_axis_offset
     *
     * Title offsets, relative to plot area:
     *     x_title_top_offset, x_title_bot_offset
     *     y_title_left_offset, y_title_left_offset
     *
     *  Note: The margins are calculated, but not stored, if margins or plot area were
     *  set by the user with SetPlotAreaPixels or SetMarginsPixels. The margin
     *  calculation is mixed in with the offset variables, so it doesn't seem worth the
     *  trouble to separate them.
     *
     * If the $maximize argument is true, we use the full image size, minus safe_margin
     * and main title, for the plot. This is for pie charts which have no axes or X/Y titles.
     */
    protected function CalcMargins($maximize)
    {
        // This is the line-to-line or line-to-text spacing:
        $gap = $this->safe_margin;

        // Minimum margin on each side. This reduces the chance that the
        // right-most tick label (for example) will run off the image edge
        // if there are no titles on that side.
        $min_margin = 3 * $gap;

        // Calculate the title sizes:
        list($unused, $title_height) = $this->SizeText($this->fonts['title'], 0, $this->title_txt);
        list($unused, $x_title_height) = $this->SizeText($this->fonts['x_title'], 0, $this->x_title_txt);
        list($y_title_width, $unused) = $this->SizeText($this->fonts['y_title'], 90, $this->y_title_txt);

        // Special case for maximum area usage with no X/Y titles or labels, only main title:
        if ($maximize) {
            if (!isset($this->x_left_margin))
                $this->x_left_margin = $gap;
            if (!isset($this->x_right_margin))
                $this->x_right_margin = $gap;
            if (!isset($this->y_top_margin)) {
                $this->y_top_margin = $gap;
                if ($title_height > 0)
                    $this->y_top_margin += $title_height + $gap;
            }
            if (!isset($this->y_bot_margin))
                $this->y_bot_margin = $gap;

            return TRUE;
        }

        // Make local variables for these. (They get used a lot and I'm tired of this, this, this.)
        $x_tick_label_pos = $this->x_tick_label_pos;
        $x_data_label_pos = $this->x_data_label_pos;
        $x_tick_pos       = $this->x_tick_pos;
        $x_tick_len       = $this->x_tick_length;
        $y_tick_label_pos = $this->y_tick_label_pos;
        $y_tick_pos       = $this->y_tick_pos;
        $y_tick_len       = $this->y_tick_length;

        // For X/Y tick and label position of 'xaxis' or 'yaxis', determine if the axis happens to be
        // on an edge of a plot. If it is, we need to account for the margins there.
        if ($this->x_axis_position <= $this->plot_min_y)
            $x_axis_pos = 'bottom';
        elseif ($this->x_axis_position >= $this->plot_max_y)
            $x_axis_pos = 'top';
        else
            $x_axis_pos = 'none';
        if ($this->y_axis_position <= $this->plot_min_x)
            $y_axis_pos = 'left';
        elseif ($this->y_axis_position >= $this->plot_max_x)
            $y_axis_pos = 'right';
        else
            $y_axis_pos = 'none';

        // Calculate the heights for X tick and data labels, and the max (used if they are overlaid):
        $x_data_label_height = ($x_data_label_pos == 'none') ? 0 : $this->CalcMaxDataLabelSize();
        $x_tick_label_height = ($x_tick_label_pos == 'none') ? 0 : $this->CalcMaxTickLabelSize('x');
        $x_max_label_height = max($x_data_label_height, $x_tick_label_height);

        // Calcualte the width for Y tick labels, if on:
        $y_label_width = ($y_tick_label_pos == 'none') ? 0 : $this->CalcMaxTickLabelSize('y');


        // Calculate the space needed above and below the plot for X tick and X data labels:

        // Above the plot:
        $tick_labels_above = ($x_tick_label_pos == 'plotup' || $x_tick_label_pos == 'both'
                          || ($x_tick_label_pos == 'xaxis' && $x_axis_pos == 'top'));
        $data_labels_above = ($x_data_label_pos == 'plotup' || $x_data_label_pos == 'both');
        if ($tick_labels_above) {
            if ($data_labels_above) {
                $label_height_above = $x_max_label_height;
            } else {
                $label_height_above = $x_tick_label_height;
            }
        } elseif ($data_labels_above) {
            $label_height_above = $x_data_label_height;
        } else {
            $label_height_above = 0;
        }

        // Below the plot:
        $tick_labels_below = ($x_tick_label_pos == 'plotdown' || $x_tick_label_pos == 'both'
                          || ($x_tick_label_pos == 'xaxis' && $x_axis_pos == 'bottom'));
        $data_labels_below = ($x_data_label_pos == 'plotdown' || $x_data_label_pos == 'both');
        if ($tick_labels_below) {
            if ($data_labels_below) {
                $label_height_below = $x_max_label_height;
            } else {
                $label_height_below = $x_tick_label_height;
            }
        } elseif ($data_labels_below) {
            $label_height_below = $x_data_label_height;
        } else {
            $label_height_below = 0;
        }

        // Calculate the space needed left and right of the plot for Y tick labels:
        // (This is simpler than X, because Y data labels don't enter the picture.)

        // Left of the plot:
        if ($y_tick_label_pos == 'plotleft' || $y_tick_label_pos == 'both'
                || ($y_tick_label_pos == 'yaxis' && $y_axis_pos == 'left')) {
            $label_width_left = $y_label_width;
        } else {
            $label_width_left = 0;
        }

        // Right of the plot:
        if ($y_tick_label_pos == 'plotright' || $y_tick_label_pos == 'both'
                || ($y_tick_label_pos == 'yaxis' && $y_axis_pos == 'right')) {
            $label_width_right = $y_label_width;
        } else {
            $label_width_right = 0;
        }

        ///////// Calculate margins:

        // Calculating Top and Bottom margins:
        // y_top_margin: Main title, Upper X title, X ticks and tick labels, and X data labels:
        // y_bot_margin: Lower title, ticks and tick labels, and data labels:
        $top_margin = $gap;
        $bot_margin = $gap;
        $this->x_title_top_offset = $gap;
        $this->x_title_bot_offset = $gap;

        // Space for main title?
        if ($title_height > 0)
            $top_margin += $title_height + $gap;

        // Space for X Title?
        if ($x_title_height > 0) {
            $pos = $this->x_title_pos;
            if ($pos == 'plotup' || $pos == 'both')
                $top_margin += $x_title_height + $gap;
            if ($pos == 'plotdown' || $pos == 'both')
                $bot_margin += $x_title_height + $gap;
        }

        // Space for X Labels above the plot?
        if ($label_height_above > 0) {
            $top_margin += $label_height_above + $gap;
            $this->x_title_top_offset += $label_height_above + $gap;
        }

        // Space for X Labels below the plot?
        if ($label_height_below > 0) {
            $bot_margin += $label_height_below + $gap;
            $this->x_title_bot_offset += $label_height_below + $gap;
        }

        // Space for X Ticks above the plot?
        if ($x_tick_pos == 'plotup' || $x_tick_pos == 'both'
           || ($x_tick_pos == 'xaxis' && $x_axis_pos == 'top')) {
            $top_margin += $x_tick_len;
            $this->x_label_top_offset = $x_tick_len + $gap;
            $this->x_title_top_offset += $x_tick_len;
        } else {
            // No X Ticks above the plot:
            $this->x_label_top_offset = $gap;
        }

        // Space for X Ticks below the plot?
        if ($x_tick_pos == 'plotdown' || $x_tick_pos == 'both'
           || ($x_tick_pos == 'xaxis' && $x_axis_pos == 'bottom')) {
            $bot_margin += $x_tick_len;
            $this->x_label_bot_offset = $x_tick_len + $gap;
            $this->x_title_bot_offset += $x_tick_len;
        } else {
            // No X Ticks below the plot:
            $this->x_label_bot_offset = $gap;
        }
        // Label offsets for on-axis ticks:
        if ($x_tick_pos == 'xaxis') {
            $this->x_label_axis_offset = $x_tick_len + $gap;
        } else {
            $this->x_label_axis_offset = $gap;
        }

        // Calculating Left and Right margins:
        // x_left_margin: Left Y title, Y ticks and tick labels:
        // x_right_margin: Right Y title, Y ticks and tick labels:
        $left_margin = $gap;
        $right_margin = $gap;
        $this->y_title_left_offset = $gap;
        $this->y_title_right_offset = $gap;

        // Space for Y Title?
        if ($y_title_width > 0) {
            $pos = $this->y_title_pos;
            if ($pos == 'plotleft' || $pos == 'both')
                $left_margin += $y_title_width + $gap;
            if ($pos == 'plotright' || $pos == 'both')
                $right_margin += $y_title_width + $gap;
        }

        // Space for Y Labels left of the plot?
        if ($label_width_left > 0) {
            $left_margin += $label_width_left + $gap;
            $this->y_title_left_offset += $label_width_left + $gap;
        }

        // Space for Y Labels right of the plot?
        if ($label_width_right > 0) {
            $right_margin += $label_width_right + $gap;
            $this->y_title_right_offset += $label_width_right + $gap;
        }

        // Space for Y Ticks left of plot?
        if ($y_tick_pos == 'plotleft' || $y_tick_pos == 'both'
           || ($y_tick_pos == 'yaxis' && $y_axis_pos == 'left')) {
            $left_margin += $y_tick_len;
            $this->y_label_left_offset = $y_tick_len + $gap;
            $this->y_title_left_offset += $y_tick_len;
        } else {
            // No Y Ticks left of plot:
            $this->y_label_left_offset = $gap;
        }

        // Space for Y Ticks right of plot?
        if ($y_tick_pos == 'plotright' || $y_tick_pos == 'both'
           || ($y_tick_pos == 'yaxis' && $y_axis_pos == 'right')) {
            $right_margin += $y_tick_len;
            $this->y_label_right_offset = $y_tick_len + $gap;
            $this->y_title_right_offset += $y_tick_len;
        } else {
            // No Y Ticks right of plot:
            $this->y_label_right_offset = $gap;
        }

        // Label offsets for on-axis ticks:
        if ($x_tick_pos == 'yaxis') {
            $this->y_label_axis_offset = $y_tick_len + $gap;
        } else {
            $this->y_label_axis_offset = $gap;
        }

        // Apply the minimum margins and store in the object.
        // Do not set margins which were user-defined (see note at top of function).
        if (!isset($this->y_top_margin))
            $this->y_top_margin = max($min_margin, $top_margin);
        if (!isset($this->y_bot_margin))
            $this->y_bot_margin = max($min_margin, $bot_margin);
        if (!isset($this->x_left_margin))
            $this->x_left_margin = max($min_margin, $left_margin);
        if (!isset($this->x_right_margin))
            $this->x_right_margin = max($min_margin, $right_margin);

        if ($this->GetCallback('debug_scale')) {
            // (Too bad compact() doesn't work on class member variables...)
            $this->DoCallback('debug_scale', __FUNCTION__, array(
                'label_height_above' => $label_height_above,
                'label_height_below' => $label_height_below,
                'label_width_left' => $label_width_left,
                'label_width_right' => $label_width_right,
                'x_tick_len' => $x_tick_len,
                'y_tick_len' => $y_tick_len,
                'x_left_margin' => $this->x_left_margin,
                'x_right_margin' => $this->x_right_margin,
                'y_top_margin' => $this->y_top_margin,
                'y_bot_margin' => $this->y_bot_margin,
                'x_label_top_offset' => $this->x_label_top_offset,
                'x_label_bot_offset' => $this->x_label_bot_offset,
                'y_label_left_offset' => $this->y_label_left_offset,
                'y_label_right_offset' => $this->y_label_right_offset,
                'x_title_top_offset' => $this->x_title_top_offset,
                'x_title_bot_offset' => $this->x_title_bot_offset,
                'y_title_left_offset' => $this->y_title_left_offset,
                'y_title_right_offset' => $this->y_title_right_offset));
        }

        return TRUE;
    }

    /*
     * Calculate the plot area (device coordinates) from the margins.
     * (This used to be part of SetPlotAreaPixels.)
     * The margins might come from SetMarginsPixels, SetPlotAreaPixels,
     * or CalcMargins.
     */
    protected function CalcPlotAreaPixels()
    {
        $this->plot_area = array($this->x_left_margin, $this->y_top_margin,
                                 $this->image_width - $this->x_right_margin,
                                 $this->image_height - $this->y_bot_margin);
        $this->plot_area_width = $this->plot_area[2] - $this->plot_area[0];
        $this->plot_area_height = $this->plot_area[3] - $this->plot_area[1];

        $this->DoCallback('debug_scale', __FUNCTION__, $this->plot_area);
        return TRUE;
    }


    /*!
     * Set the margins in pixels (left, right, top, bottom)
     * This determines the plot area, equivalent to SetPlotAreaPixels().
     * Deferred calculations now occur in CalcPlotAreaPixels().
     */
    function SetMarginsPixels($which_lm = NULL, $which_rm = NULL, $which_tm = NULL, $which_bm = NULL)
    {
        $this->x_left_margin = $which_lm;
        $this->x_right_margin = $which_rm;
        $this->y_top_margin = $which_tm;
        $this->y_bot_margin = $which_bm;

        return TRUE;
    }

    /*!
     * Sets the limits for the plot area.
     * This stores the margins, not the area. That may seem odd, but
     * the idea is to make SetPlotAreaPixels and SetMarginsPixels two
     * ways to accomplish the same thing, and the deferred calculations
     * in CalcMargins and CalcPlotAreaPixels don't need to know which
     * was used.
     *   (x1, y1) - Upper left corner of the plot area
     *   (x2, y2) - Lower right corner of the plot area
     */
    function SetPlotAreaPixels($x1 = NULL, $y1 = NULL, $x2 = NULL, $y2 = NULL)
    {
        $this->x_left_margin = $x1;
        if (isset($x2)) $this->x_right_margin = $this->image_width - $x2;
        else unset($this->x_right_margin);
        $this->y_top_margin = $y1;
        if (isset($y2)) $this->y_bot_margin = $this->image_height - $y2;
        else unset($this->y_bot_margin);

        return TRUE;
    }

    /*
     * Calculate the World Coordinate limits of the plot area.
     * This goes with SetPlotAreaWorld, but the calculations are
     * deferred until the graph is being drawn.
     * Uses: plot_min_x, plot_max_x, plot_min_y, plot_max_y
     * which can be user-supplied or NULL to auto-calculate.
     * Pre-requisites: FindDataLimits()
     */
    protected function CalcPlotAreaWorld()
    {
        if (isset($this->plot_min_x) && $this->plot_min_x !== '')
            $xmin = $this->plot_min_x;
        elseif ($this->data_type == 'text-data')  // Valid for data without X values only.
            $xmin = 0;
        else
            $xmin = $this->min_x;

        if (isset($this->plot_max_x) && $this->plot_max_x !== '')
            $xmax = $this->plot_max_x;
        elseif ($this->data_type == 'text-data')  // Valid for data without X values only.
            $xmax = $this->max_x + 1;
        else
            $xmax = $this->max_x;

        // Leave room above and below the highest and lowest data points.

        if (!isset($this->plot_min_y) || $this->plot_min_y === '')
            $ymin = floor($this->min_y - abs($this->min_y) * 0.1);
        else
            $ymin = $this->plot_min_y;

        if (!isset($this->plot_max_y) || $this->plot_max_y === '')
            $ymax = ceil($this->max_y + abs($this->max_y) * 0.1);
        else
            $ymax = $this->plot_max_y;

        // Error checking

        if ($ymin == $ymax)
            $ymax++;
        if ($xmin == $xmax)
            $xmax++;

        if ($this->yscale_type == 'log') {
            if ($ymin <= 0) {
                $ymin = 1;
            }
            if ($ymax <= 0) {
                // Note: Error messages reference the user function, not this function.
                return $this->PrintError('SetPlotAreaWorld(): Log plots need data greater than 0');
            }
        }

        if ($ymax <= $ymin) {
            return $this->PrintError('SetPlotAreaWorld(): Error in data - max not greater than min');
        }

        $this->plot_min_x = $xmin;
        $this->plot_max_x = $xmax;
        $this->plot_min_y = $ymin;
        $this->plot_max_y = $ymax;
        if ($this->GetCallback('debug_scale')) {
            $this->DoCallback('debug_scale', __FUNCTION__, array(
                'plot_min_x' => $this->plot_min_x, 'plot_min_y' => $this->plot_min_y,
                'plot_max_x' => $this->plot_max_x, 'plot_max_y' => $this->plot_max_y));
        }

        return TRUE;
    }

    /*!
     * Stores the desired World Coordinate range of the plot.
     * The user calls this to force one or more of the range limits to
     * specific values. Anything not set will be calculated in CalcPlotAreaWorld().
     */
    function SetPlotAreaWorld($xmin=NULL, $ymin=NULL, $xmax=NULL, $ymax=NULL)
    {
        $this->plot_min_x = $xmin;
        $this->plot_max_x = $xmax;
        $this->plot_min_y = $ymin;
        $this->plot_max_y = $ymax;
        return TRUE;
    } //function SetPlotAreaWorld


    /*!
     * For bar plots, which have equally spaced x variables.
     */
    protected function CalcBarWidths()
    {
        // group_width is the width of a group, including padding
        $group_width = $this->plot_area_width / $this->num_data_rows;

        // Actual number of bar spaces in the group. This includes the drawn bars, and
        // 'bar_extra_space'-worth of extra bars.
        // Note that 'records_per_group' includes the label, so subtract one to get
        // the number of points in the group. 'stackedbars' have 1 bar space per group.
        if ($this->plot_type == 'stackedbars') {
          $num_spots = 1 + $this->bar_extra_space;
        } else {
          $num_spots = $this->records_per_group - 1 + $this->bar_extra_space;
        }

        // record_bar_width is the width of each bar's allocated area.
        // If bar_width_adjust=1 this is the width of the bar, otherwise
        // the bar is centered inside record_bar_width.
        // The equation is:
        //   group_frac_width * group_width = record_bar_width * num_spots
        $this->record_bar_width = $this->group_frac_width * $group_width / $num_spots;

        // Note that the extra space due to group_frac_width and bar_extra_space will be
        // evenly divided on each side of the group: the drawn bars are centered in the group.

        // Within each bar's allocated space, if bar_width_adjust=1 the bar fills the
        // space, otherwise it is centered.
        // This is the actual drawn bar width:
        $this->actual_bar_width = $this->record_bar_width * $this->bar_width_adjust;
        // This is the gap on each side of the bar (0 if bar_width_adjust=1):
        $this->bar_adjust_gap = ($this->record_bar_width - $this->actual_bar_width) / 2;

        return TRUE;
    }

    /*
     * Calculate X and Y Axis Positions, world coordinates.
     * This needs the min/max x/y range set by CalcPlotAreaWorld.
     * It adjusts or sets x_axis_position and y_axis_position per the data.
     * Empty string means the values need to be calculated; otherwise they
     * are supplied but need to be validated against the World area.
     *
     * Note: This used to be in CalcTranslation, but CalcMargins needs it too.
     * This does not calculate the pixel values of the axes. That happens in
     * CalcTranslation, after scaling is set up (which has to happen after
     * margins are set up).
     */
    protected function CalcAxisPositions()
    {
        // If no user-provided Y axis position, default to axis on left side.
        // Otherwise, make sure user-provided position is inside the plot area.
        if ($this->y_axis_position === '')
            $this->y_axis_position = $this->plot_min_x;
        else
            $this->y_axis_position = min(max($this->plot_min_x, $this->y_axis_position), $this->plot_max_x);

        // If no user-provided X axis position, default to axis at Y=0 (if in range), or min_y
        //   if the range does not include 0, or 1 for log plots.
        // Otherwise, make sure user-provided position is inside the plot area.
        if ($this->x_axis_position === '') {
            if ($this->yscale_type == 'log')
                $this->x_axis_position = 1;
            elseif ($this->plot_min_y <= 0 && 0 <= $this->plot_max_y)
                $this->x_axis_position = 0;
             else
                $this->x_axis_position = $this->plot_min_y;
        } else
            $this->x_axis_position = min(max($this->plot_min_y, $this->x_axis_position), $this->plot_max_y);

        if ($this->GetCallback('debug_scale')) {
            $this->DoCallback('debug_scale', __FUNCTION__, array(
                'x_axis_position' => $this->x_axis_position,
                'y_axis_position' => $this->y_axis_position));
        }

        return TRUE;
    }

    /*!
     * Calculates scaling stuff...
     */
    protected function CalcTranslation()
    {
        if ($this->plot_max_x - $this->plot_min_x == 0) { // Check for div by 0
            $this->xscale = 0;
        } else {
            if ($this->xscale_type == 'log') {
                $this->xscale = ($this->plot_area_width)/(log10($this->plot_max_x) - log10($this->plot_min_x));
            } else {
                $this->xscale = ($this->plot_area_width)/($this->plot_max_x - $this->plot_min_x);
            }
        }

        if ($this->plot_max_y - $this->plot_min_y == 0) { // Check for div by 0
            $this->yscale = 0;
        } else {
            if ($this->yscale_type == 'log') {
                $this->yscale = ($this->plot_area_height)/(log10($this->plot_max_y) - log10($this->plot_min_y));
            } else {
                $this->yscale = ($this->plot_area_height)/($this->plot_max_y - $this->plot_min_y);
            }
        }
        // GD defines x = 0 at left and y = 0 at TOP so -/+ respectively
        if ($this->xscale_type == 'log') {
            $this->plot_origin_x = $this->plot_area[0] - ($this->xscale * log10($this->plot_min_x) );
        } else {
            $this->plot_origin_x = $this->plot_area[0] - ($this->xscale * $this->plot_min_x);
        }
        if ($this->yscale_type == 'log') {
            $this->plot_origin_y = $this->plot_area[3] + ($this->yscale * log10($this->plot_min_y));
        } else {
            $this->plot_origin_y = $this->plot_area[3] + ($this->yscale * $this->plot_min_y);
        }

        // Convert axis positions to device coordinates:
        $this->y_axis_x_pixels = $this->xtr($this->y_axis_position);
        $this->x_axis_y_pixels = $this->ytr($this->x_axis_position);

        if ($this->GetCallback('debug_scale')) {
            $this->DoCallback('debug_scale', __FUNCTION__, array(
                'xscale' => $this->xscale, 'yscale' => $this->yscale,
                'plot_origin_x' => $this->plot_origin_x, 'plot_origin_y' => $this->plot_origin_y,
                'y_axis_x_pixels' => $this->y_axis_x_pixels,
                'x_axis_y_pixels' => $this->x_axis_y_pixels));
        }

        return TRUE;
    } // function CalcTranslation()


    /*!
     * Translate X world coordinate into pixel coordinate
     * See CalcTranslation() for calculation of xscale.
     */
    function xtr($x_world)
    {
        if ($this->xscale_type == 'log') {
            $x_pixels = $this->plot_origin_x + log10($x_world) * $this->xscale ;
        } else {
            $x_pixels = $this->plot_origin_x + $x_world * $this->xscale ;
        }
        return round($x_pixels);
    }


    /*!
     * Translate Y world coordinate into pixel coordinate.
     * See CalcTranslation() for calculation of yscale.
     */
    function ytr($y_world)
    {
        if ($this->yscale_type == 'log') {
            //minus because GD defines y = 0 at top. doh!
            $y_pixels =  $this->plot_origin_y - log10($y_world) * $this->yscale ;
        } else {
            $y_pixels =  $this->plot_origin_y - $y_world * $this->yscale ;
        }
        return round($y_pixels);
    }

    /* A public interface to xtr and ytr. Translates (x,y) in world coordinates
     * to (x,y) in device coordinates and returns them as an array.
     * Usage is: list($x_pixel, $y_pixel) = $plot->GetDeviceXY($x_world, $y_world)
     */
    function GetDeviceXY($x_world, $y_world)
    {
        if (!isset($this->xscale)) {
            return $this->PrintError("GetDeviceXY() was called before translation factors were calculated");
        }
        return array($this->xtr($x_world), $this->ytr($y_world));
    }

    /*
     * Calculate tick parameters: Start, end, and delta values. This is used
     * by both DrawXTicks() and DrawYTicks().
     * This currently uses the same simplistic method previously used by
     * PHPlot (basically just range/10), but splitting this out into its
     * own function is the first step in replacing the method.
     * This is also used by CalcMaxTickSize() for CalcMargins().
     *
     *   $which : 'x' or 'y' : Which tick parameters to calculate
     *
     * Returns an array of 3 elements: tick_start, tick_end, tick_step
     */
    protected function CalcTicks($which)
    {
        if ($which == 'x') {
            $num_ticks = $this->num_x_ticks;
            $tick_inc = $this->x_tick_inc;
            $data_max = $this->plot_max_x;
            $data_min = $this->plot_min_x;
            $skip_lo = $this->skip_left_tick;
            $skip_hi = $this->skip_right_tick;
        } elseif ($which == 'y') {
            $num_ticks = $this->num_y_ticks;
            $tick_inc = $this->y_tick_inc;
            $data_max = $this->plot_max_y;
            $data_min = $this->plot_min_y;
            $skip_lo = $this->skip_bottom_tick;
            $skip_hi = $this->skip_top_tick;
        } else {
          return $this->PrintError("CalcTicks: Invalid usage ($which)");
        }

        if (!empty($tick_inc)) {
            $tick_step = $tick_inc;
        } elseif (!empty($num_ticks)) {
            $tick_step = ($data_max - $data_min) / $num_ticks;
        } else {
            $tick_step = ($data_max - $data_min) / 10;
        }

        // NOTE: When working with floats, because of approximations when adding $tick_step,
        // the value may not quite reach the end, or may exceed it very slightly.
        // So apply a "fudge" factor.
        $tick_start = (double)$data_min;
        $tick_end = (double)$data_max + ($data_max - $data_min) / 10000.0;

        if ($skip_lo)
            $tick_start += $tick_step;

        if ($skip_hi)
            $tick_end -= $tick_step;

        return array($tick_start, $tick_end, $tick_step);
    }

    /*
     * Calculate the size of the biggest tick label. This is used by CalcMargins().
     * For 'x' ticks, it returns the height . For 'y' ticks, it returns the width.
     * This means height along Y, or width along X - not relative to the text angle.
     * That is what we need to calculate the needed margin space.
     * (Previous versions of PHPlot estimated this, using the maximum X or Y value,
     * or maybe the longest string. That doesn't work. -10 is longer than 9, etc.
     * So this gets the actual size of each label, slow as that may be.
     */
    protected function CalcMaxTickLabelSize($which)
    {
        list($tick_start, $tick_end, $tick_step) = $this->CalcTicks($which);

        if ($which == 'x') {
          $font = $this->fonts['x_label'];
          $angle = $this->x_label_angle;
        } elseif ($which == 'y') {
          $font = $this->fonts['y_label'];
          $angle = $this->y_label_angle;
        } else {
          return $this->PrintError("CalcMaxTickLabelSize: Invalid usage ($which)");
        }

        $max_width = 0;
        $max_height = 0;

        // Loop over ticks, same as DrawXTicks and DrawYTicks:
        // Avoid cumulative round-off errors from $val += $delta
        $n = 0;
        $tick_val = $tick_start;
        while ($tick_val <= $tick_end) {
            $tick_label = $this->FormatLabel($which, $tick_val);
            list($width, $height) = $this->SizeText($font, $angle, $tick_label);
            if ($width > $max_width) $max_width = $width;
            if ($height > $max_height) $max_height = $height;
            $tick_val = $tick_start + ++$n * $tick_step;
        }
        if ($this->GetCallback('debug_scale')) {
            $this->DoCallback('debug_scale', __FUNCTION__, array(
                'which' => $which, 'height' => $max_height, 'width' => $max_width));
        }

        if ($which == 'x')
            return $max_height;
        return $max_width;
    }

    /*
     * Calculate the size of the biggest X data label. This is used by CalcMargins().
     * Returns the height along Y axis of the biggest X data label.
     * (This calculates width and height, but only height is used at present.)
     */
    protected function CalcMaxDataLabelSize()
    {
        $font = $this->fonts['x_label'];
        $angle = $this->x_data_label_angle;
        $max_width = 0;
        $max_height = 0;

        // Loop over all data labels and find the biggest:
        for ($i = 0; $i < $this->num_data_rows; $i++) {
            $label = $this->FormatLabel('xd', $this->data[$i][0]);
            list($width, $height) = $this->SizeText($font, $angle, $label);
            if ($width > $max_width) $max_width = $width;
            if ($height > $max_height) $max_height = $height;
        }
        if ($this->GetCallback('debug_scale')) {
            $this->DoCallback('debug_scale', __FUNCTION__, array(
                'height' => $max_height, 'width' => $max_width));
        }

        return $max_height;
    }

    /*
     * Check and set label parameters. This handles deferred processing for label
     * positioning and other label-related parameters.
     *   Copy label_format from 'x' to 'xd', and 'y' to 'yd', if not already set.
     *   Set x_data_label_angle from x_label_angle, if not already set.
     *   Apply defaults to X tick and data label positions.
     */
    protected function CheckLabels()
    {
        // The X and Y data labels are formatted the same as X and Y tick labels,
        // unless overridden. Check and apply defaults for FormatLabel here:
        if (empty($this->label_format['xd']) && !empty($this->label_format['x']))
            $this->label_format['xd'] = $this->label_format['x'];
        if (empty($this->label_format['yd']) && !empty($this->label_format['y']))
            $this->label_format['yd'] = $this->label_format['y'];

        // The X tick label angle setting controls X data label angles too,
        // unless overridden. Check and apply the default here:
        if (!isset($this->x_data_label_angle))
            $this->x_data_label_angle = $this->x_label_angle;
        // Note: Y data label angle defaults to zero, unlike X,
        // for compatibility with older releases.

        // X Label position fixups, for x_data_label_pos and x_tick_label_pos:

        if (isset($this->x_data_label_pos)) {

            if (!isset($this->x_tick_label_pos)) {
                // Case: data_label_pos is set, tick_label_pos needs a default:
                if ($this->x_data_label_pos == 'none')
                    $this->x_tick_label_pos = 'plotdown';
                else
                    $this->x_tick_label_pos = 'none';
            }

        } elseif (isset($this->x_tick_label_pos)) {
            // Case: tick_label_pos is set, data_label_pos needs a default:
            if ($this->x_tick_label_pos == 'none')
                $this->x_data_label_pos = 'plotdown';
            else
                $this->x_data_label_pos = 'none';

        } else {
            // Case: Neither tick_label_pos nor data_label_pos is set.
            // We do not want them to be both on (as PHPlot used to do in this case).
            // Turn on data labels if any were supplied, else tick labels.
            $data_labels_empty = TRUE;
            for ($i = 0; $data_labels_empty && $i < $this->num_data_rows; $i++)
                $data_labels_empty = ($this->data[$i][0] === '');
            if ($data_labels_empty) {
                $this->x_data_label_pos = 'none';
                $this->x_tick_label_pos = 'plotdown';
            } else {
                $this->x_data_label_pos = 'plotdown';
                $this->x_tick_label_pos = 'none';
            }
        }
        return TRUE;
    }

    /*!
     * Formats a tick or data label.
     *    which_pos - 'x', 'xd', 'y', or 'yd', selects formatting controls.
     *        x, y are for tick labels; xd, yd are for data labels.
     *    which_lab - String to format as a label.
     * Credits: Time formatting suggested by Marlin Viss
     *          Custom formatting suggested by zer0x333
     * Notes:
     *   Type 'title' is obsolete and retained for compatibility.
     *   Class variable 'data_units_text' is retained as a suffix for 'data' type formatting for
     *      backward compatibility. Since there was never a function/method to set it, there
     *      could be somebody out there who sets it directly in the object.
     */
    protected function FormatLabel($which_pos, $which_lab)
    {
        // Assign a reference shortcut to the label format controls.
        // Note CheckLabels() made sure the 'xd' and 'yd' arrays are set.
        $format =& $this->label_format[$which_pos];

        // Don't format empty strings (especially as time or numbers), or if no type was set.
        if ($which_lab !== '' && !empty($format['type'])) {
            switch ($format['type']) {
            case 'title':  // Note: This is obsolete
                $which_lab = @ $this->data[$which_lab][0];
                break;
            case 'data':
                $which_lab = $format['prefix']
                           . $this->number_format($which_lab, $format['precision'])
                           . $this->data_units_text  // Obsolete
                           . $format['suffix'];
                break;
            case 'time':
                $which_lab = strftime($format['time_format'], $which_lab);
                break;
            case 'printf':
                $which_lab = sprintf($format['printf_format'], $which_lab);
                break;
            case 'custom':
                $which_lab = call_user_func($format['custom_callback'], $which_lab, $format['custom_arg']);
                break;

            }
        }
        return $which_lab;
    } //function FormatLabel

/////////////////////////////////////////////
///////////////                         TICKS
/////////////////////////////////////////////

    /*!
     * Use either this or SetNumXTicks() to set where to place x tick marks
     */
    function SetXTickIncrement($which_ti='')
    {
        $this->x_tick_inc = $which_ti;
        if (!empty($which_ti)) {
            $this->num_x_ticks = ''; //either use num_x_ticks or x_tick_inc, not both
        }
        return TRUE;
    }

    /*!
     * Use either this or SetNumYTicks() to set where to place y tick marks
     */
    function SetYTickIncrement($which_ti='')
    {
        $this->y_tick_inc = $which_ti;
        if (!empty($which_ti)) {
            $this->num_y_ticks = ''; //either use num_y_ticks or y_tick_inc, not both
        }
        return TRUE;
    }


    function SetNumXTicks($which_nt)
    {
        $this->num_x_ticks = $which_nt;
        if (!empty($which_nt)) {
            $this->x_tick_inc = '';  //either use num_x_ticks or x_tick_inc, not both
        }
        return TRUE;
    }

    function SetNumYTicks($which_nt)
    {
        $this->num_y_ticks = $which_nt;
        if (!empty($which_nt)) {
            $this->y_tick_inc = '';  //either use num_y_ticks or y_tick_inc, not both
        }
        return TRUE;
    }

    /*!
     *
     */
    function SetYTickPos($which_tp)
    {
        $this->y_tick_pos = $this->CheckOption($which_tp, 'plotleft, plotright, both, yaxis, none', __FUNCTION__);
        return (boolean)$this->y_tick_pos;
    }
    /*!
     *
     */
    function SetXTickPos($which_tp)
    {
        $this->x_tick_pos = $this->CheckOption($which_tp, 'plotdown, plotup, both, xaxis, none', __FUNCTION__);
        return (boolean)$this->x_tick_pos;
    }

    /*!
     * \param skip bool
     */
    function SetSkipTopTick($skip)
    {
        $this->skip_top_tick = (bool)$skip;
        return TRUE;
    }

    /*!
     * \param skip bool
     */
    function SetSkipBottomTick($skip)
    {
        $this->skip_bottom_tick = (bool)$skip;
        return TRUE;
    }

    /*!
     * \param skip bool
     */
    function SetSkipLeftTick($skip)
    {
        $this->skip_left_tick = (bool)$skip;
        return TRUE;
    }

    /*!
     * \param skip bool
     */
    function SetSkipRightTick($skip)
    {
        $this->skip_right_tick = (bool)$skip;
        return TRUE;
    }

    function SetXTickLength($which_xln)
    {
        $this->x_tick_length = $which_xln;
        return TRUE;
    }

    function SetYTickLength($which_yln)
    {
        $this->y_tick_length = $which_yln;
        return TRUE;
    }

    function SetXTickCrossing($which_xc)
    {
        $this->x_tick_cross = $which_xc;
        return TRUE;
    }

    function SetYTickCrossing($which_yc)
    {
        $this->y_tick_cross = $which_yc;
        return TRUE;
    }


/////////////////////////////////////////////
////////////////////          GENERIC DRAWING
/////////////////////////////////////////////

    /*!
     * Fills the background.
     * Note: This method should be 'protected', but is called from test script(s).
     */
    function DrawBackground()
    {
        // Don't draw this twice if drawing two plots on one image
        if (! $this->background_done) {
            if (isset($this->bgimg)) {    // If bgimg is defined, use it
                $this->tile_img($this->bgimg, 0, 0, $this->image_width, $this->image_height, $this->bgmode);
            } else {                        // Else use solid color
                ImageFilledRectangle($this->img, 0, 0, $this->image_width, $this->image_height,
                                     $this->ndx_bg_color);
            }
            $this->background_done = TRUE;
        }
        return TRUE;
    }


    /*!
     * Fills the plot area background.
     */
    protected function DrawPlotAreaBackground()
    {
        if (isset($this->plotbgimg)) {
            $this->tile_img($this->plotbgimg, $this->plot_area[0], $this->plot_area[1],
                            $this->plot_area_width, $this->plot_area_height, $this->plotbgmode);
        }
        else {
            if ($this->draw_plot_area_background) {
                ImageFilledRectangle($this->img, $this->plot_area[0], $this->plot_area[1],
                                     $this->plot_area[2], $this->plot_area[3], $this->ndx_plot_bg_color);
            }
        }

        return TRUE;
    }


    /*!
     * Tiles an image at some given coordinates.
     *
     * \param $file   string Filename of the picture to be used as tile.
     * \param $xorig  int    X coordinate of the plot where the tile is to begin.
     * \param $yorig  int    Y coordinate of the plot where the tile is to begin.
     * \param $width  int    Width of the area to be tiled.
     * \param $height int    Height of the area to be tiled.
     * \param $mode   string One of 'centeredtile', 'tile', 'scale'.
     */
    protected function tile_img($file, $xorig, $yorig, $width, $height, $mode)
    {
        $im = $this->GetImage($file, $tile_width, $tile_height);
        if (!$im)
            return FALSE;  // GetImage already produced an error message.

        if ($mode == 'scale') {
            imagecopyresized($this->img, $im, $xorig, $yorig, 0, 0, $width, $height, $tile_width, $tile_height);
            return TRUE;
        } else if ($mode == 'centeredtile') {
            $x0 = - floor($tile_width/2);   // Make the tile look better
            $y0 = - floor($tile_height/2);
        } else if ($mode = 'tile') {
            $x0 = 0;
            $y0 = 0;
        }

        // Actually draw the tile

        // But first on a temporal image.
        $tmp = ImageCreate($width, $height);
        if (! $tmp)
            return $this->PrintError('tile_img(): Could not create image resource.');

        for ($x = $x0; $x < $width; $x += $tile_width)
            for ($y = $y0; $y < $height; $y += $tile_height)
                imagecopy($tmp, $im, $x, $y, 0, 0, $tile_width, $tile_height);

        // Copy the temporal image onto the final one.
        imagecopy($this->img, $tmp, $xorig, $yorig, 0,0, $width, $height);

        // Free resources
        imagedestroy($tmp);
        imagedestroy($im);

        return TRUE;
    }  // function tile_img


    /*!
     * Draws a border around the final image.
     */
    protected function DrawImageBorder()
    {
        switch ($this->image_border_type) {
        case 'raised':
            ImageLine($this->img, 0, 0, $this->image_width-1, 0, $this->ndx_i_border);
            ImageLine($this->img, 1, 1, $this->image_width-2, 1, $this->ndx_i_border);
            ImageLine($this->img, 0, 0, 0, $this->image_height-1, $this->ndx_i_border);
            ImageLine($this->img, 1, 1, 1, $this->image_height-2, $this->ndx_i_border);
            ImageLine($this->img, $this->image_width-1, 0, $this->image_width-1,
                      $this->image_height-1, $this->ndx_i_border_dark);
            ImageLine($this->img, 0, $this->image_height-1, $this->image_width-1,
                      $this->image_height-1, $this->ndx_i_border_dark);
            ImageLine($this->img, $this->image_width-2, 1, $this->image_width-2,
                      $this->image_height-2, $this->ndx_i_border_dark);
            ImageLine($this->img, 1, $this->image_height-2, $this->image_width-2,
                      $this->image_height-2, $this->ndx_i_border_dark);
            break;
        case 'plain':
            ImageLine($this->img, 0, 0, $this->image_width-1, 0, $this->ndx_i_border_dark);
            ImageLine($this->img, $this->image_width-1, 0, $this->image_width-1,
                      $this->image_height-1, $this->ndx_i_border_dark);
            ImageLine($this->img, $this->image_width-1, $this->image_height-1, 0, $this->image_height-1,
                      $this->ndx_i_border_dark);
            ImageLine($this->img, 0, 0, 0, $this->image_height-1, $this->ndx_i_border_dark);
            break;
        case 'none':
            break;
        default:
            return $this->PrintError("DrawImageBorder(): unknown image_border_type: '$this->image_border_type'");
        }
        return TRUE;
    }


    /*!
     * Adds the title to the graph.
     */
    protected function DrawTitle()
    {
        // Center of the plot area
        //$xpos = ($this->plot_area[0] + $this->plot_area_width )/ 2;

        // Center of the image:
        $xpos = $this->image_width / 2;

        // Place it at almost at the top
        $ypos = $this->safe_margin;

        $this->DrawText($this->fonts['title'], 0, $xpos, $ypos,
                        $this->ndx_title_color, $this->title_txt, 'center', 'top');

        return TRUE;

    }


    /*!
     * Draws the X-Axis Title
     */
    protected function DrawXTitle()
    {
        if ($this->x_title_pos == 'none')
            return TRUE;

        // Center of the plot
        $xpos = ($this->plot_area[2] + $this->plot_area[0]) / 2;

        // Upper title
        if ($this->x_title_pos == 'plotup' || $this->x_title_pos == 'both') {
            $ypos = $this->plot_area[1] - $this->x_title_top_offset;
            $this->DrawText($this->fonts['x_title'], 0, $xpos, $ypos, $this->ndx_title_color,
                            $this->x_title_txt, 'center', 'bottom');
        }
        // Lower title
        if ($this->x_title_pos == 'plotdown' || $this->x_title_pos == 'both') {
            $ypos = $this->plot_area[3] + $this->x_title_bot_offset;
            $this->DrawText($this->fonts['x_title'], 0, $xpos, $ypos, $this->ndx_title_color,
                            $this->x_title_txt, 'center', 'top');
        }
        return TRUE;
    }

    /*!
     * Draws the Y-Axis Title
     */
    protected function DrawYTitle()
    {
        if ($this->y_title_pos == 'none')
            return TRUE;

        // Center the title vertically to the plot area
        $ypos = ($this->plot_area[3] + $this->plot_area[1]) / 2;

        if ($this->y_title_pos == 'plotleft' || $this->y_title_pos == 'both') {
            $xpos = $this->plot_area[0] - $this->y_title_left_offset;
            $this->DrawText($this->fonts['y_title'], 90, $xpos, $ypos, $this->ndx_title_color,
                            $this->y_title_txt, 'right', 'center');
        }
        if ($this->y_title_pos == 'plotright' || $this->y_title_pos == 'both') {
            $xpos = $this->plot_area[2] + $this->y_title_right_offset;
            $this->DrawText($this->fonts['y_title'], 90, $xpos, $ypos, $this->ndx_title_color,
                            $this->y_title_txt, 'left', 'center');
        }

        return TRUE;
    }


    /*
     * \note Horizontal grid lines overwrite horizontal axis with y=0, so call this first, then DrawXAxis()
     */
    protected function DrawYAxis()
    {
        // Draw ticks, labels and grid, if any
        $this->DrawYTicks();

        // Draw Y axis at X = y_axis_x_pixels
        ImageLine($this->img, $this->y_axis_x_pixels, $this->plot_area[1],
                  $this->y_axis_x_pixels, $this->plot_area[3], $this->ndx_grid_color);

        return TRUE;
    }

    /*
     *
     */
    protected function DrawXAxis()
    {
        // Draw ticks, labels and grid
        $this->DrawXTicks();

        /* This tick and label tend to overlap with regular Y Axis labels,
         * as Mike Pullen pointed out.
         *
        //Draw Tick and Label for X axis
        if (! $this->skip_bottom_tick) {
            $ylab =$this->FormatLabel('y', $this->x_axis_position);
            $this->DrawYTick($ylab, $this->x_axis_y_pixels);
        }
        */
        //Draw X Axis at Y = x_axis_y_pixels
        ImageLine($this->img, $this->plot_area[0]+1, $this->x_axis_y_pixels,
                  $this->plot_area[2]-1, $this->x_axis_y_pixels, $this->ndx_grid_color);

        return TRUE;
    }

    /*!
     * Draw one Y tick mark and its tick label. Called from DrawYTicks() and DrawXAxis()
     */
    protected function DrawYTick($which_ylab, $which_ypix)
    {
        // Ticks on Y axis
        if ($this->y_tick_pos == 'yaxis') {
            ImageLine($this->img, $this->y_axis_x_pixels - $this->y_tick_length, $which_ypix,
                      $this->y_axis_x_pixels + $this->y_tick_cross, $which_ypix, $this->ndx_tick_color);
        }

        // Ticks to the left of the Plot Area
        if (($this->y_tick_pos == 'plotleft') || ($this->y_tick_pos == 'both') ) {
            ImageLine($this->img, $this->plot_area[0] - $this->y_tick_length, $which_ypix,
                      $this->plot_area[0] + $this->y_tick_cross, $which_ypix, $this->ndx_tick_color);
        }

        // Ticks to the right of the Plot Area
        if (($this->y_tick_pos == 'plotright') || ($this->y_tick_pos == 'both') ) {
            ImageLine($this->img, $this->plot_area[2] + $this->y_tick_length, $which_ypix,
                      $this->plot_area[2] - $this->y_tick_cross, $which_ypix, $this->ndx_tick_color);
        }

        // Labels on Y axis
        if ($this->y_tick_label_pos == 'yaxis') {
            $this->DrawText($this->fonts['y_label'], $this->y_label_angle,
                            $this->y_axis_x_pixels - $this->y_label_axis_offset, $which_ypix,
                            $this->ndx_text_color, $which_ylab, 'right', 'center');
        }

        // Labels to the left of the plot area
        if ($this->y_tick_label_pos == 'plotleft' || $this->y_tick_label_pos == 'both') {
            $this->DrawText($this->fonts['y_label'], $this->y_label_angle,
                            $this->plot_area[0] - $this->y_label_left_offset, $which_ypix,
                            $this->ndx_text_color, $which_ylab, 'right', 'center');
        }
        // Labels to the right of the plot area
        if ($this->y_tick_label_pos == 'plotright' || $this->y_tick_label_pos == 'both') {
            $this->DrawText($this->fonts['y_label'], $this->y_label_angle,
                            $this->plot_area[2] + $this->y_label_right_offset, $which_ypix,
                            $this->ndx_text_color, $which_ylab, 'left', 'center');
        }
        return TRUE;
    } // Function DrawYTick()


    /*!
     * Draws Grid, Ticks and Tick Labels along Y-Axis
     * Ticks and ticklabels can be left of plot only, right of plot only,
     * both on the left and right of plot, or crossing a user defined Y-axis
     */
    protected function DrawYTicks()
    {
        // Sets the line style for IMG_COLOR_STYLED lines (grid)
        if ($this->dashed_grid) {
            $this->SetDashedStyle($this->ndx_light_grid_color);
            $style = IMG_COLOR_STYLED;
        } else {
            $style = $this->ndx_light_grid_color;
        }

        // Calculate the tick start, end, and step:
        list($y_start, $y_end, $delta_y) = $this->CalcTicks('y');

        // Loop, avoiding cumulative round-off errors from $y_tmp += $delta_y
        $n = 0;
        $y_tmp = $y_start;
        while ($y_tmp <= $y_end) {
            $ylab = $this->FormatLabel('y', $y_tmp);
            $y_pixels = $this->ytr($y_tmp);

            // Horizontal grid line
            if ($this->draw_y_grid) {
                ImageLine($this->img, $this->plot_area[0]+1, $y_pixels, $this->plot_area[2]-1, $y_pixels, $style);
            }

            // Draw tick mark(s)
            $this->DrawYTick($ylab, $y_pixels);

            // Step to next Y, without accumulating error
            $y_tmp = $y_start + ++$n * $delta_y;
        }
        return TRUE;
    } // function DrawYTicks

    /*!
     * Draw one X tick mark and its tick label.
     */
    protected function DrawXTick($which_xlab, $which_xpix)
    {
        // Ticks on X axis
        if ($this->x_tick_pos == 'xaxis') {
            ImageLine($this->img, $which_xpix, $this->x_axis_y_pixels - $this->x_tick_cross,
                      $which_xpix, $this->x_axis_y_pixels + $this->x_tick_length, $this->ndx_tick_color);
        }

        // Ticks on top of the Plot Area
        if ($this->x_tick_pos == 'plotup' || $this->x_tick_pos == 'both') {
            ImageLine($this->img, $which_xpix, $this->plot_area[1] - $this->x_tick_length,
                      $which_xpix, $this->plot_area[1] + $this->x_tick_cross, $this->ndx_tick_color);
        }

        // Ticks on bottom of Plot Area
        if ($this->x_tick_pos == 'plotdown' || $this->x_tick_pos == 'both') {
            ImageLine($this->img, $which_xpix, $this->plot_area[3] + $this->x_tick_length,
                      $which_xpix, $this->plot_area[3] - $this->x_tick_cross, $this->ndx_tick_color);
        }

        // Label on X axis
        if ($this->x_tick_label_pos == 'xaxis') {
            $this->DrawText($this->fonts['x_label'], $this->x_label_angle,
                            $which_xpix, $this->x_axis_y_pixels + $this->x_label_axis_offset,
                            $this->ndx_text_color, $which_xlab, 'center', 'top');
        }

        // Label on top of the Plot Area
        if ($this->x_tick_label_pos == 'plotup' || $this->x_tick_label_pos == 'both') {
            $this->DrawText($this->fonts['x_label'], $this->x_label_angle,
                            $which_xpix, $this->plot_area[1] - $this->x_label_top_offset,
                            $this->ndx_text_color, $which_xlab, 'center', 'bottom');
        }

        // Label on bottom of the Plot Area
        if ($this->x_tick_label_pos == 'plotdown' || $this->x_tick_label_pos == 'both') {
            $this->DrawText($this->fonts['x_label'], $this->x_label_angle,
                            $which_xpix, $this->plot_area[3] + $this->x_label_bot_offset,
                            $this->ndx_text_color, $which_xlab, 'center', 'top');
        }
        return TRUE;
    }

    /*!
     * Draws Grid, Ticks and Tick Labels along X-Axis
     * Ticks and tick labels can be down of plot only, up of plot only,
     * both on up and down of plot, or crossing a user defined X-axis
     *
     * \note Original vertical code submitted by Marlin Viss
     */
    protected function DrawXTicks()
    {
        // Sets the line style for IMG_COLOR_STYLED lines (grid)
        if ($this->dashed_grid) {
            $this->SetDashedStyle($this->ndx_light_grid_color);
            $style = IMG_COLOR_STYLED;
        } else {
            $style = $this->ndx_light_grid_color;
        }

        // Calculate the tick start, end, and step:
        list($x_start, $x_end, $delta_x) = $this->CalcTicks('x');

        // Loop, avoiding cumulative round-off errors from $x_tmp += $delta_x
        $n = 0;
        $x_tmp = $x_start;
        while ($x_tmp <= $x_end) {
            $xlab = $this->FormatLabel('x', $x_tmp);
            $x_pixels = $this->xtr($x_tmp);

            // Vertical grid lines
            if ($this->draw_x_grid) {
                ImageLine($this->img, $x_pixels, $this->plot_area[1], $x_pixels, $this->plot_area[3], $style);
            }

            // Draw tick mark(s)
            $this->DrawXTick($xlab, $x_pixels);

            // Step to next X, without accumulating error
            $x_tmp = $x_start + ++$n * $delta_x;
        }
        return TRUE;
    } // function DrawXTicks


    /*!
     *
     */
    protected function DrawPlotBorder()
    {
        switch ($this->plot_border_type) {
        case 'left':    // for past compatibility
        case 'plotleft':
            ImageLine($this->img, $this->plot_area[0], $this->ytr($this->plot_min_y),
                      $this->plot_area[0], $this->ytr($this->plot_max_y), $this->ndx_grid_color);
            break;
        case 'right':
        case 'plotright':
            ImageLine($this->img, $this->plot_area[2], $this->ytr($this->plot_min_y),
                      $this->plot_area[2], $this->ytr($this->plot_max_y), $this->ndx_grid_color);
            break;
        case 'both':
        case 'sides':
             ImageLine($this->img, $this->plot_area[0], $this->ytr($this->plot_min_y),
                      $this->plot_area[0], $this->ytr($this->plot_max_y), $this->ndx_grid_color);
            ImageLine($this->img, $this->plot_area[2], $this->ytr($this->plot_min_y),
                      $this->plot_area[2], $this->ytr($this->plot_max_y), $this->ndx_grid_color);
            break;
        case 'none':
            //Draw No Border
            break;
        case 'full':
        default:
            ImageRectangle($this->img, $this->plot_area[0], $this->ytr($this->plot_min_y),
                           $this->plot_area[2], $this->ytr($this->plot_max_y), $this->ndx_grid_color);
            break;
        }
        return TRUE;
    }


    /*!
     * Draws the data label associated with a point in the plot at specified x/y world position.
     * This is currently only used for Y data labels for bar charts.
     */
    protected function DrawDataLabel($which_font, $which_angle, $x_world, $y_world, $which_color, $which_text,
                      $which_halign = 'center', $which_valign = 'bottom', $x_adjustment=0, $y_adjustment=0)
    {
        $this->DrawText($which_font, $which_angle,
                        $this->xtr($x_world) + $x_adjustment, $this->ytr($y_world) + $y_adjustment,
                        $which_color, $this->FormatLabel('yd', $which_text), $which_halign, $which_valign);

        return TRUE;
    }

    /*!
     * Draws the data label associated with a point in the plot.
     * This is different from x_labels drawn by DrawXTicks() and care
     * should be taken not to draw both, as they'd probably overlap.
     * Calling of this function in DrawLines(), etc is decided after x_data_label_pos value.
     * Leave the last parameter out, to avoid the drawing of vertical lines, no matter
     * what the setting is (for plots that need it, like DrawSquared())
     */
    protected function DrawXDataLabel($xlab, $xpos, $row=FALSE)
    {
        $xlab = $this->FormatLabel('xd', $xlab);

        // Labels below the plot area
        if ($this->x_data_label_pos == 'plotdown' || $this->x_data_label_pos == 'both')
            $this->DrawText($this->fonts['x_label'], $this->x_data_label_angle,
                            $xpos, $this->plot_area[3] + $this->x_label_bot_offset,
                            $this->ndx_text_color, $xlab, 'center', 'top');

        // Labels above the plot area
        if ($this->x_data_label_pos == 'plotup' || $this->x_data_label_pos == 'both')
            $this->DrawText($this->fonts['x_label'], $this->x_data_label_angle,
                            $xpos, $this->plot_area[1] - $this->x_label_top_offset,
                            $this->ndx_text_color, $xlab, 'center', 'bottom');

        // $row=0 means this is the first row. $row=FALSE means don't do any rows.
        if ($row !== FALSE && $this->draw_x_data_label_lines)
            $this->DrawXDataLine($xpos, $row);
        return TRUE;
    }

    /*!
     * Draws Vertical lines from data points up and down.
     * Which lines are drawn depends on the value of x_data_label_pos,
     * and whether this is at all done or not, on draw_x_data_label_lines
     *
     * \param xpos int position in pixels of the line.
     * \param row int index of the data row being drawn.
     */
    protected function DrawXDataLine($xpos, $row)
    {
        // Sets the line style for IMG_COLOR_STYLED lines (grid)
        if($this->dashed_grid) {
            $this->SetDashedStyle($this->ndx_light_grid_color);
            $style = IMG_COLOR_STYLED;
        } else {
            $style = $this->ndx_light_grid_color;
        }

        // Lines from the bottom up
        if ($this->x_data_label_pos == 'both') {
            ImageLine($this->img, $xpos, $this->plot_area[3], $xpos, $this->plot_area[1], $style);
        }
        // Lines from the bottom of the plot up to the max Y value at this X:
        else if ($this->x_data_label_pos == 'plotdown' && isset($this->data_maxy[$row])) {
            $ypos = $this->ytr($this->data_maxy[$row]);
            ImageLine($this->img, $xpos, $ypos, $xpos, $this->plot_area[3], $style);
        }
        // Lines from the top of the plot down to the min Y value at this X:
        else if ($this->x_data_label_pos == 'plotup' && isset($this->data_miny[$row])) {
            $ypos = $this->ytr($this->data_miny[$row]);
            ImageLine($this->img, $xpos, $this->plot_area[1], $xpos, $ypos, $style);
        }
        return TRUE;
    }


    /*!
     * Draws the graph legend
     *
     * \note Base code submitted by Marlin Viss
     */
    protected function DrawLegend()
    {
        $font = &$this->fonts['legend'];

        // Find maximum legend label line width.
        $max_width = 0;
        foreach ($this->legend as $line) {
            list($width, $unused) = $this->SizeText($font, 0, $line);
            if ($width > $max_width) $max_width = $width;
        }

        // Use the font parameters to size the color boxes:
        $char_w = $font['width'];
        $char_h = $font['height'];
        $line_spacing = $this->GetLineSpacing($font);

        // Normalize text alignment and colorbox alignment variables:
        $text_align = isset($this->legend_text_align) ? $this->legend_text_align : 'right';
        $colorbox_align = isset($this->legend_colorbox_align) ? $this->legend_colorbox_align : 'right';

        // Sizing parameters:
        $v_margin = $char_h/2;                   // Between vertical borders and labels
        $dot_height = $char_h + $line_spacing;   // Height of the small colored boxes
        // Overall legend box width e.g.: | space colorbox space text space |
        // where colorbox and each space are 1 char width.
        if ($colorbox_align != 'none') {
            $width = $max_width + 4 * $char_w;
            $draw_colorbox = True;
        } else {
            $width = $max_width + 2 * $char_w;
            $draw_colorbox = False;
        }

        //////// Calculate box position
        // User-defined position specified?
        if ( !isset($this->legend_x_pos) || !isset($this->legend_y_pos)) {
            // No, use default
            $box_start_x = $this->plot_area[2] - $width - $this->safe_margin;
            $box_start_y = $this->plot_area[1] + $this->safe_margin;
        } elseif (isset($this->legend_xy_world)) {
            // User-defined position in world-coordinates (See SetLegendWorld).
            $box_start_x = $this->xtr($this->legend_x_pos);
            $box_start_y = $this->ytr($this->legend_y_pos);
            unset($this->legend_xy_world);
        } else {
            // User-defined position in pixel coordinates.
            $box_start_x = $this->legend_x_pos;
            $box_start_y = $this->legend_y_pos;
        }

        // Lower right corner
        $box_end_y = $box_start_y + $dot_height*(count($this->legend)) + 2*$v_margin;
        $box_end_x = $box_start_x + $width;

        // Draw outer box
        ImageFilledRectangle($this->img, $box_start_x, $box_start_y, $box_end_x, $box_end_y, $this->ndx_bg_color);
        ImageRectangle($this->img, $box_start_x, $box_start_y, $box_end_x, $box_end_y, $this->ndx_grid_color);

        $color_index = 0;
        $max_color_index = count($this->ndx_data_colors) - 1;

        // Calculate color box and text horizontal positions.
        if (!$draw_colorbox) {
            if ($text_align == 'left')
                $x_pos = $box_start_x + $char_w;
            else
                $x_pos = $box_end_x - $char_w;
        } elseif ($colorbox_align == 'left') {
            $dot_left_x = $box_start_x + $char_w;
            $dot_right_x = $dot_left_x + $char_w;
            if ($text_align == 'left')
                $x_pos = $dot_left_x + 2 * $char_w;
            else
                $x_pos = $box_end_x - $char_w;
        } else {
            $dot_left_x = $box_end_x - 2 * $char_w;
            $dot_right_x = $dot_left_x + $char_w;
            if ($text_align == 'left')
                $x_pos = $box_start_x + $char_w;
            else
                $x_pos = $dot_left_x - $char_w;
        }

        // Calculate starting position of first text line.  The bottom of each color box
        // lines up with the bottom (baseline) of its text line.
        $y_pos = $box_start_y + $v_margin + $dot_height;

        foreach ($this->legend as $leg) {
            // Draw text with requested alignment:
            $this->DrawText($font, 0, $x_pos, $y_pos, $this->ndx_text_color, $leg, $text_align, 'bottom');
            if ($draw_colorbox) {
                // Draw a box in the data color
                $y1 = $y_pos - $dot_height + 1;
                $y2 = $y_pos - 1;
                ImageFilledRectangle($this->img, $dot_left_x, $y1, $dot_right_x, $y2,
                                     $this->ndx_data_colors[$color_index]);
                // Draw a rectangle around the box
                ImageRectangle($this->img, $dot_left_x, $y1, $dot_right_x, $y2,
                               $this->ndx_text_color);
            }
            $y_pos += $dot_height;

            $color_index++;
            if ($color_index > $max_color_index)
                $color_index = 0;
        }
        return TRUE;
    } // Function DrawLegend()


/////////////////////////////////////////////
////////////////////             PLOT DRAWING
/////////////////////////////////////////////


    /*!
     * Draws a pie chart. Data is 'text-data', 'data-data', or 'text-data-single'.
     *
     *  For text-data-single, the data array contains records with an ignored label,
     *  and one Y value. Each record defines a sector of the pie, as a portion of
     *  the sum of all Y values.
     *
     *  For text-data and data-data, the data array contains records with an ignored label,
     *  an ignored X value (for data-data only), and N (N>=1) Y values per record.
     *  The pie chart will be produced with N segments. The relative size of the first
     *  sector of the pie is the sum of the first Y data value in each record, etc.
     *  
     *  Note: With text-data-single, the data labels could be used, but are not currently.
     *
     *  If there are no valid data points > 0 at all, just draw nothing. It may seem more correct to
     *  raise an error, but all of the other plot types handle it this way implicitly. DrawGraph
     *  checks for an empty data array, but this is different: a non-empty data array with no Y values,
     *  or all Y=0.
     */
    protected function DrawPieChart()
    {
        $xpos = $this->plot_area[0] + $this->plot_area_width/2;
        $ypos = $this->plot_area[1] + $this->plot_area_height/2;
        $diameter = min($this->plot_area_width, $this->plot_area_height);
        $radius = $diameter/2;

        // Get sum of each column? One pie slice per column
        if ($this->data_type == 'text-data') {
            $num_slices = $this->records_per_group - 1;  // records_per_group is the maximum row size
            if ($num_slices < 1) return TRUE;            // Give up early if there is no data at all.
            $sumarr = array_fill(0, $num_slices, 0);
            for ($i = 0; $i < $this->num_data_rows; $i++) {
                for ($j = 1; $j < $this->num_recs[$i]; $j++) {  // Skip label at [0]
                    if (is_numeric($this->data[$i][$j]))
                        $sumarr[$j-1] += abs($this->data[$i][$j]);
                }
            }
        }
        // Or only one column per row, one pie slice per row?
        else if ($this->data_type == 'text-data-single') {
            $num_slices = $this->num_data_rows;
            if ($num_slices < 1) return TRUE;            // Give up early if there is no data at all.
            $sumarr = array_fill(0, $num_slices, 0);
            for ($i = 0; $i < $num_slices; $i++) {
                // $legend[$i] = $this->data[$i][0];                // Note: Labels are not used yet
                if (is_numeric($this->data[$i][1]))
                    $sumarr[$i] = abs($this->data[$i][1]);
            }
        }
        else if ($this->data_type == 'data-data') {
            $num_slices = $this->records_per_group - 2;  // records_per_group is the maximum row size
            if ($num_slices < 1) return TRUE;            // Give up early if there is no data at all.
            $sumarr = array_fill(0, $num_slices, 0);
            for ($i = 0; $i < $this->num_data_rows; $i++) {
                for ($j = 2; $j < $this->num_recs[$i]; $j++) {  // Skip label at [0] an X and [1]
                    if (is_numeric($this->data[$i][$j]))
                        $sumarr[$j-2] += abs($this->data[$i][$j]);
                }
            }
        }
        else {
            return $this->PrintError("DrawPieChart(): Data type '$this->data_type' not supported.");
        }

        $total = array_sum($sumarr);

        if ($total == 0) {
            // There are either no valid data points, or all are 0.
            // See top comment about why not to make this an error.
            return TRUE;
        }

        if ($this->shading) {
            $diam2 = $diameter / 2;
        } else {
            $diam2 = $diameter;
        }
        $max_data_colors = count ($this->data_colors);

        // Use the Y label format precision, with default value:
        if (isset($this->label_format['y']['precision']))
            $precision = $this->label_format['y']['precision'];
        else
            $precision = 1;


        for ($h = $this->shading; $h >= 0; $h--) {
            $color_index = 0;
            $start_angle = 0;
            $end_angle = 0;
            for ($j = 0; $j < $num_slices; $j++) {
                $val = $sumarr[$j];

                // For shaded pies: the last one (at the top of the "stack") has a brighter color:
                if ($h == 0)
                    $slicecol = $this->ndx_data_colors[$color_index];
                else
                    $slicecol = $this->ndx_data_dark_colors[$color_index];

                $label_txt = $this->number_format(($val / $total * 100), $precision) . '%';
                $val = 360 * ($val / $total);

                // NOTE that imagefilledarc measures angles CLOCKWISE (go figure why),
                // so the pie chart would start clockwise from 3 o'clock, would it not be
                // for the reversal of start and end angles in imagefilledarc()
                // Also note ImageFilledArc only takes angles in integer degrees, and if the
                // the start and end angles match then you get a full circle not a zero-width
                // pie. This is bad. So skip any zero-size wedge. On the other hand, we cannot
                // let cumulative error from rounding to integer result in missing wedges. So
                // keep the running total as a float, and round the angles. It should not
                // be necessary to check that the last wedge ends at 360 degrees.
                $start_angle = $end_angle;
                $end_angle += $val;
                // This method of conversion to integer - truncate after reversing it - was
                // chosen to match the implicit method of PHPlot<=5.0.4 to get the same slices.
                $arc_start_angle = (int)(360 - $start_angle);
                $arc_end_angle = (int)(360 - $end_angle);

                if ($arc_start_angle > $arc_end_angle) {
                    $mid_angle = deg2rad($end_angle - ($val / 2));

                    // Draw the slice
                    ImageFilledArc($this->img, $xpos, $ypos+$h, $diameter, $diam2,
                                   $arc_end_angle, $arc_start_angle,
                                   $slicecol, IMG_ARC_PIE);

                    // Draw the labels only once
                    if ($h == 0) {
                        // Draw the outline
                        if (! $this->shading)
                            ImageFilledArc($this->img, $xpos, $ypos+$h, $diameter, $diam2,
                                           $arc_end_angle, $arc_start_angle,
                                           $this->ndx_grid_color, IMG_ARC_PIE | IMG_ARC_EDGED |IMG_ARC_NOFILL);


                        // The '* 1.2' trick is to get labels out of the pie chart so there are more
                        // chances they can be seen in small sectors.
                        $label_x = $xpos + ($diameter * 1.2 * cos($mid_angle)) * $this->label_scale_position;
                        $label_y = $ypos+$h - ($diam2 * 1.2 * sin($mid_angle)) * $this->label_scale_position;

                        $this->DrawText($this->fonts['generic'], 0, $label_x, $label_y, $this->ndx_grid_color,
                                        $label_txt, 'center', 'center');
                    }
                }
                if (++$color_index >= $max_data_colors)
                    $color_index = 0;
            }   // end for
        }   // end for
        return TRUE;
    }


    /*!
     * Supported data formats: data-data-error, text-data-error (doesn't exist yet)
     * ( data comes in as array("title", x, y, error+, error-, y2, error2+, error2-, ...) )
     */
    protected function DrawDotsError()
    {
        if ($this->data_type != 'data-data-error') {
            return $this->PrintError("DrawDotsError(): Data type '$this->data_type' not supported.");
        }

        // Adjust the point shapes and point sizes arrays:
        $this->CheckPointParams();

        // Suppress duplicate X data labels in linepoints mode; let DrawLinesError() do them.
        $do_labels = ($this->plot_type != 'linepoints');

        for($row = 0, $cnt = 0; $row < $this->num_data_rows; $row++) {
            $record = 1;                                // Skip record #0 (title)

            $x_now = $this->data[$row][$record++];  // Read it, advance record index

            $x_now_pixels = $this->xtr($x_now);             // Absolute coordinates.

            // Draw X Data labels?
            if ($this->x_data_label_pos != 'none' && $do_labels)
                $this->DrawXDataLabel($this->data[$row][0], $x_now_pixels, $row);

            // Now go for Y, E+, E-
            for ($idx = 0; $record < $this->num_recs[$row]; $idx++) {
                if (is_numeric($this->data[$row][$record])) {         // Allow for missing Y data

                    // Y:
                    $y_now = $this->data[$row][$record++];
                    $this->DrawDot($x_now, $y_now, $idx, $this->ndx_data_colors[$idx]);

                    // Error +
                    $val = $this->data[$row][$record++];
                    $this->DrawYErrorBar($x_now, $y_now, $val, $this->error_bar_shape,
                                         $this->ndx_error_bar_colors[$idx]);
                    // Error -
                    $val = $this->data[$row][$record++];
                    $this->DrawYErrorBar($x_now, $y_now, -$val, $this->error_bar_shape,
                                         $this->ndx_error_bar_colors[$idx]);
                } else {
                    $record += 3;  // Skip over missing Y and its error values
                }
            }
        }
        return TRUE;
    } // function DrawDotsError()


    /*
     * Supported data types:
     *  - data-data ("title", x, y1, y2, y3, ...)
     *  - text-data ("title", y1, y2, y3, ...)
     */
    protected function DrawDots()
    {
        if (!$this->CheckOption($this->data_type, 'text-data, data-data', __FUNCTION__))
            return FALSE;

        // Adjust the point shapes and point sizes arrays:
        $this->CheckPointParams();

        // Suppress duplicate X data labels in linepoints mode; let DrawLines() do them.
        $do_labels = ($this->plot_type != 'linepoints');

        for ($row = 0, $cnt = 0; $row < $this->num_data_rows; $row++) {
            $rec = 1;                    // Skip record #0 (data label)

            // Do we have a value for X?
            if ($this->data_type == 'data-data')
                $x_now = $this->data[$row][$rec++];  // Read it, advance record index
            else
                $x_now = 0.5 + $cnt++;       // Place text-data at X = 0.5, 1.5, 2.5, etc...

            $x_now_pixels = $this->xtr($x_now);

            // Draw X Data labels?
            if ($this->x_data_label_pos != 'none' && $do_labels)
                $this->DrawXDataLabel($this->data[$row][0], $x_now_pixels, $row);

            // Proceed with Y values
            for($idx = 0;$rec < $this->num_recs[$row]; $rec++, $idx++) {
                if (is_numeric($this->data[$row][$rec])) {              // Allow for missing Y data
                    $this->DrawDot($x_now, $this->data[$row][$rec],
                                   $idx, $this->ndx_data_colors[$idx]);
                }
            }
        }
        return TRUE;
    } //function DrawDots


    /*!
     * A clean, fast routine for when you just want charts like stock volume charts
     */
    protected function DrawThinBarLines()
    {
        if (!$this->CheckOption($this->data_type, 'text-data, data-data', __FUNCTION__))
            return FALSE;

        for ($row = 0, $cnt = 0; $row < $this->num_data_rows; $row++) {
            $rec = 1;                    // Skip record #0 (data label)

            // Do we have a value for X?
            if ($this->data_type == 'data-data')
                $x_now = $this->data[$row][$rec++];  // Read it, advance record index
            else
                $x_now = 0.5 + $cnt++;       // Place text-data at X = 0.5, 1.5, 2.5, etc...

            $x_now_pixels = $this->xtr($x_now);

            // Draw X Data labels?
            if ($this->x_data_label_pos != 'none')
                $this->DrawXDataLabel($this->data[$row][0], $x_now_pixels);

            // Proceed with Y values
            for($idx = 0;$rec < $this->num_recs[$row]; $rec++, $idx++) {
                if (is_numeric($this->data[$row][$rec])) {              // Allow for missing Y data
                    ImageSetThickness($this->img, $this->line_widths[$idx]);
                    // Draws a line from user defined x axis position up to ytr($val)
                    ImageLine($this->img, $x_now_pixels, $this->x_axis_y_pixels, $x_now_pixels,
                              $this->ytr($this->data[$row][$rec]), $this->ndx_data_colors[$idx]);
                }
            }
        }

        ImageSetThickness($this->img, 1);
        return TRUE;
    }  //function DrawThinBarLines

    /*!
     *
     */
    protected function DrawYErrorBar($x_world, $y_world, $error_height, $error_bar_type, $color)
    {
        /*
        // TODO: add a parameter to show datalabels next to error bars?
        // something like this:
        if ($this->x_data_label_pos == 'plot')
            $this->DrawText($this->fonts['error'], 90, $x1, $y2,
                            $color, $label, 'center', 'bottom');
        */

        $x1 = $this->xtr($x_world);
        $y1 = $this->ytr($y_world);
        $y2 = $this->ytr($y_world+$error_height) ;

        ImageSetThickness($this->img, $this->error_bar_line_width);
        ImageLine($this->img, $x1, $y1 , $x1, $y2, $color);

        switch ($error_bar_type) {
        case 'line':
            break;
        case 'tee':
            ImageLine($this->img, $x1-$this->error_bar_size, $y2, $x1+$this->error_bar_size, $y2, $color);
            break;
        default:
            ImageLine($this->img, $x1-$this->error_bar_size, $y2, $x1+$this->error_bar_size, $y2, $color);
            break;
        }

        ImageSetThickness($this->img, 1);
        return TRUE;
    }

    /*!
     * Draws a styled dot. Uses world coordinates.
     * The list of supported shapes can also be found in SetPointShapes().
     * All shapes are drawn using a 3x3 grid, centered on the data point.
     * The center is (x_mid, y_mid) and the corners are (x1, y1) and (x2, y2).
     *   $record is the 0-based index that selects the shape and size.
     */
    protected function DrawDot($x_world, $y_world, $record, $color)
    {
        $index = $record % $this->point_counts;
        $point_size = $this->point_sizes[$index];

        $half_point = (int)($point_size / 2);

        $x_mid = $this->xtr($x_world);
        $y_mid = $this->ytr($y_world);

        $x1 = $x_mid - $half_point;
        $x2 = $x_mid + $half_point;
        $y1 = $y_mid - $half_point;
        $y2 = $y_mid + $half_point;

        switch ($this->point_shapes[$index]) {
        case 'halfline':
            ImageLine($this->img, $x1, $y_mid, $x_mid, $y_mid, $color);
            break;
        case 'line':
            ImageLine($this->img, $x1, $y_mid, $x2, $y_mid, $color);
            break;
        case 'plus':
            ImageLine($this->img, $x1, $y_mid, $x2, $y_mid, $color);
            ImageLine($this->img, $x_mid, $y1, $x_mid, $y2, $color);
            break;
        case 'cross':
            ImageLine($this->img, $x1, $y1, $x2, $y2, $color);
            ImageLine($this->img, $x1, $y2, $x2, $y1, $color);
            break;
        case 'circle':
            ImageArc($this->img, $x_mid, $y_mid, $point_size, $point_size, 0, 360, $color);
            break;
        case 'dot':
            ImageFilledArc($this->img, $x_mid, $y_mid, $point_size, $point_size, 0, 360, $color, IMG_ARC_PIE);
            break;
        case 'diamond':
            $arrpoints = array( $x1, $y_mid, $x_mid, $y1, $x2, $y_mid, $x_mid, $y2);
            ImageFilledPolygon($this->img, $arrpoints, 4, $color);
            break;
        case 'triangle':
            $arrpoints = array( $x1, $y_mid, $x2, $y_mid, $x_mid, $y2);
            ImageFilledPolygon($this->img, $arrpoints, 3, $color);
            break;
        case 'trianglemid':
            $arrpoints = array( $x1, $y1, $x2, $y1, $x_mid, $y_mid);
            ImageFilledPolygon($this->img, $arrpoints, 3, $color);
            break;
        case 'yield':
            $arrpoints = array( $x1, $y1, $x2, $y1, $x_mid, $y2);
            ImageFilledPolygon($this->img, $arrpoints, 3, $color);
            break;
        case 'delta':
            $arrpoints = array( $x1, $y2, $x2, $y2, $x_mid, $y1);
            ImageFilledPolygon($this->img, $arrpoints, 3, $color);
            break;
        case 'star':
            ImageLine($this->img, $x1, $y_mid, $x2, $y_mid, $color);
            ImageLine($this->img, $x_mid, $y1, $x_mid, $y2, $color);
            ImageLine($this->img, $x1, $y1, $x2, $y2, $color);
            ImageLine($this->img, $x1, $y2, $x2, $y1, $color);
            break;
        case 'hourglass':
            $arrpoints = array( $x1, $y1, $x2, $y1, $x1, $y2, $x2, $y2);
            ImageFilledPolygon($this->img, $arrpoints, 4, $color);
            break;
        case 'bowtie':
            $arrpoints = array( $x1, $y1, $x1, $y2, $x2, $y1, $x2, $y2);
            ImageFilledPolygon($this->img, $arrpoints, 4, $color);
            break;
        case 'target':
            ImageFilledRectangle($this->img, $x1, $y1, $x_mid, $y_mid, $color);
            ImageFilledRectangle($this->img, $x_mid, $y_mid, $x2, $y2, $color);
            ImageRectangle($this->img, $x1, $y1, $x2, $y2, $color);
            break;
        case 'box':
            ImageRectangle($this->img, $x1, $y1, $x2, $y2, $color);
            break;
        case 'home': /* As in: "home plate" (baseball), also looks sort of like a house. */
            $arrpoints = array( $x1, $y2, $x2, $y2, $x2, $y_mid, $x_mid, $y1, $x1, $y_mid);
            ImageFilledPolygon($this->img, $arrpoints, 5, $color);
            break;
        case 'up':
            ImagePolygon($this->img, array($x_mid, $y1, $x2, $y2, $x1, $y2), 3, $color);
            break;
        case 'down':
            ImagePolygon($this->img, array($x_mid, $y2, $x1, $y1, $x2, $y1), 3, $color);
            break;
        case 'none': /* Special case, no point shape here */
            break;
        default: /* Also 'rect' */
            ImageFilledRectangle($this->img, $x1, $y1, $x2, $y2, $color);
            break;
        }
        return TRUE;
    }

    /*!
     * Draw an area plot. Supported data types:
     *      'text-data'
     *      'data-data'
     * NOTE: This function used to add first and last data values even on incomplete
     *       sets. That is not the behavior now. As for missing data in between,
     *       there are two possibilities: replace the point with one on the X axis (previous
     *       way), or forget about it and use the preceding and following ones to draw the polygon.
     *       There is the possibility to use both, we just need to add the method to set
     *       it. Something like SetMissingDataBehavior(), for example.
     */
    protected function DrawArea()
    {
        $incomplete_data_defaults_to_x_axis = FALSE;        // TODO: make this configurable

        for ($row = 0, $cnt = 0; $row < $this->num_data_rows; $row++) {
            $rec = 1;                                       // Skip record #0 (data label)

            if ($this->data_type == 'data-data')            // Do we have a value for X?
                $x_now = $this->data[$row][$rec++];         // Read it, advance record index
            else
                $x_now = 0.5 + $cnt++;                      // Place text-data at X = 0.5, 1.5, 2.5, etc...

            $x_now_pixels = $this->xtr($x_now);             // Absolute coordinates


            if ($this->x_data_label_pos != 'none')          // Draw X Data labels?
                $this->DrawXDataLabel($this->data[$row][0], $x_now_pixels);

            // Proceed with Y values
            // Create array of points for imagefilledpolygon()
            for($idx = 0; $rec < $this->num_recs[$row]; $rec++, $idx++) {
                if (is_numeric($this->data[$row][$rec])) {              // Allow for missing Y data
                    $y_now_pixels = $this->ytr($this->data[$row][$rec]);

                    $posarr[$idx][] = $x_now_pixels;
                    $posarr[$idx][] = $y_now_pixels;

                    $num_points[$idx] = isset($num_points[$idx]) ? $num_points[$idx]+1 : 1;
                }
                // If there's missing data...
                else {
                    if (isset ($incomplete_data_defaults_to_x_axis)) {
                        $posarr[$idx][] = $x_now_pixels;
                        $posarr[$idx][] = $this->x_axis_y_pixels;
                        $num_points[$idx] = isset($num_points[$idx]) ? $num_points[$idx]+1 : 1;
                    }
                }
            }
        }   // end for

        $end = count($posarr);
        for ($i = 0; $i < $end; $i++) {
            // Prepend initial points. X = first point's X, Y = x_axis_y_pixels
            $x = $posarr[$i][0];
            array_unshift($posarr[$i], $x, $this->x_axis_y_pixels);

            // Append final points. X = last point's X, Y = x_axis_y_pixels
            $x = $posarr[$i][count($posarr[$i])-2];
            array_push($posarr[$i], $x, $this->x_axis_y_pixels);

            $num_points[$i] += 2;

            // Draw the polygon
            ImageFilledPolygon($this->img, $posarr[$i], $num_points[$i], $this->ndx_data_colors[$i]);
        }
        return TRUE;
    } // function DrawArea()


    /*!
     * Draw Lines. Supported data-types:
     *      'data-data',
     *      'text-data'
     * NOTE: Please see the note regarding incomplete data sets on DrawArea()
     */
    protected function DrawLines()
    {
        // This will tell us if lines have already begun to be drawn.
        // It is an array to keep separate information for every line, with a single
        // variable we would sometimes get "undefined offset" errors and no plot...
        $start_lines = array_fill(0, $this->records_per_group, FALSE);

        if ($this->data_type == 'text-data') {
            $lastx[0] = $this->xtr(0);
            $lasty[0] = $this->xtr(0);
        }

        for ($row = 0, $cnt = 0; $row < $this->num_data_rows; $row++) {
            $record = 1;                                    // Skip record #0 (data label)

            if ($this->data_type == 'data-data')            // Do we have a value for X?
                $x_now = $this->data[$row][$record++];      // Read it, advance record index
            else
                $x_now = 0.5 + $cnt++;                      // Place text-data at X = 0.5, 1.5, 2.5, etc...

            $x_now_pixels = $this->xtr($x_now);             // Absolute coordinates

            if ($this->x_data_label_pos != 'none')          // Draw X Data labels?
                $this->DrawXDataLabel($this->data[$row][0], $x_now_pixels, $row);

            for ($idx = 0; $record < $this->num_recs[$row]; $record++, $idx++) {
                if (($line_style = $this->line_styles[$idx]) == 'none')
                    continue; //Allow suppressing entire line, useful with linepoints
                if (is_numeric($this->data[$row][$record])) {           //Allow for missing Y data
                    $y_now_pixels = $this->ytr($this->data[$row][$record]);

                    if ($start_lines[$idx]) {
                        // Set line width, revert it to normal at the end
                        ImageSetThickness($this->img, $this->line_widths[$idx]);

                        if ($line_style == 'dashed') {
                            $this->SetDashedStyle($this->ndx_data_colors[$idx]);
                            ImageLine($this->img, $x_now_pixels, $y_now_pixels, $lastx[$idx], $lasty[$idx],
                                      IMG_COLOR_STYLED);
                        } else {
                            ImageLine($this->img, $x_now_pixels, $y_now_pixels, $lastx[$idx], $lasty[$idx],
                                      $this->ndx_data_colors[$idx]);
                        }

                    }
                    $lasty[$idx] = $y_now_pixels;
                    $lastx[$idx] = $x_now_pixels;
                    $start_lines[$idx] = TRUE;
                }
                // Y data missing... should we leave a blank or not?
                else if ($this->draw_broken_lines) {
                    $start_lines[$idx] = FALSE;
                }
            }   // end for
        }   // end for

        ImageSetThickness($this->img, 1);       // Revert to original state for lines to be drawn later.
        return TRUE;
    } // function DrawLines()


    /*!
     * Draw lines with error bars - data comes in as
     *      array("label", x, y, error+, error-, y2, error2+, error2-, ...);
     */
    protected function DrawLinesError()
    {
        if ($this->data_type != 'data-data-error') {
            return $this->PrintError("DrawLinesError(): Data type '$this->data_type' not supported.");
        }

        $start_lines = array_fill(0, $this->records_per_group, FALSE);

        for ($row = 0, $cnt = 0; $row < $this->num_data_rows; $row++) {
            $record = 1;                                    // Skip record #0 (data label)

            $x_now = $this->data[$row][$record++];          // Read X value, advance record index

            $x_now_pixels = $this->xtr($x_now);             // Absolute coordinates.


            if ($this->x_data_label_pos != 'none')          // Draw X Data labels?
                $this->DrawXDataLabel($this->data[$row][0], $x_now_pixels, $row);

            // Now go for Y, E+, E-
            for ($idx = 0; $record < $this->num_recs[$row]; $idx++) {
                if (($line_style = $this->line_styles[$idx]) == 'none')
                    continue; //Allow suppressing entire line, useful with linepoints
                if (is_numeric($this->data[$row][$record])) {    // Allow for missing Y data

                    // Y
                    $y_now = $this->data[$row][$record++];
                    $y_now_pixels = $this->ytr($y_now);

                    if ($start_lines[$idx]) {
                        ImageSetThickness($this->img, $this->line_widths[$idx]);

                        if ($line_style == 'dashed') {
                            $this->SetDashedStyle($this->ndx_data_colors[$idx]);
                            ImageLine($this->img, $x_now_pixels, $y_now_pixels, $lastx[$idx], $lasty[$idx],
                                      IMG_COLOR_STYLED);
                        } else {
                            ImageLine($this->img, $x_now_pixels, $y_now_pixels, $lastx[$idx], $lasty[$idx],
                                      $this->ndx_data_colors[$idx]);
                        }
                    }

                    // Error+
                    $val = $this->data[$row][$record++];
                    $this->DrawYErrorBar($x_now, $y_now, $val, $this->error_bar_shape,
                                         $this->ndx_error_bar_colors[$idx]);

                    // Error-
                    $val = $this->data[$row][$record++];
                    $this->DrawYErrorBar($x_now, $y_now, -$val, $this->error_bar_shape,
                                         $this->ndx_error_bar_colors[$idx]);

                    // Update indexes:
                    $start_lines[$idx] = TRUE;   // Tells us if we already drew the first column of points,
                                             // thus having $lastx and $lasty ready for the next column.
                    $lastx[$idx] = $x_now_pixels;
                    $lasty[$idx] = $y_now_pixels;

                } else {
                    $record += 3;  // Skip over missing Y and its error values
                    if ($this->draw_broken_lines) {
                        $start_lines[$idx] = FALSE;
                    }
                }
            }   // end for
        }   // end for

        ImageSetThickness($this->img, 1);   // Revert to original state for lines to be drawn later.
        return TRUE;
    }   // function DrawLinesError()



    /*!
     * This is a mere copy of DrawLines() with one more line drawn for each point
     */
    protected function DrawSquared()
    {
        // This will tell us if lines have already begun to be drawn.
        // It is an array to keep separate information for every line, for with a single
        // variable we could sometimes get "undefined offset" errors and no plot...
        $start_lines = array_fill(0, $this->records_per_group, FALSE);

        if ($this->data_type == 'text-data') {
            $lastx[0] = $this->xtr(0);
            $lasty[0] = $this->xtr(0);
        }

        for ($row = 0, $cnt = 0; $row < $this->num_data_rows; $row++) {
            $record = 1;                                    // Skip record #0 (data label)

            if ($this->data_type == 'data-data')            // Do we have a value for X?
                $x_now = $this->data[$row][$record++];      // Read it, advance record index
            else
                $x_now = 0.5 + $cnt++;                      // Place text-data at X = 0.5, 1.5, 2.5, etc...

            $x_now_pixels = $this->xtr($x_now);             // Absolute coordinates

            if ($this->x_data_label_pos != 'none')          // Draw X Data labels?
                $this->DrawXDataLabel($this->data[$row][0], $x_now_pixels); // notice there is no last param.

            // Draw Lines
            for ($idx = 0; $record < $this->num_recs[$row]; $record++, $idx++) {
                if (is_numeric($this->data[$row][$record])) {               // Allow for missing Y data
                    $y_now_pixels = $this->ytr($this->data[$row][$record]);

                    if ($start_lines[$idx] == TRUE) {
                        // Set line width, revert it to normal at the end
                        ImageSetThickness($this->img, $this->line_widths[$idx]);

                        if ($this->line_styles[$idx] == 'dashed') {
                            $this->SetDashedStyle($this->ndx_data_colors[$idx]);
                            ImageLine($this->img, $lastx[$idx], $lasty[$idx], $x_now_pixels, $lasty[$idx],
                                      IMG_COLOR_STYLED);
                            ImageLine($this->img, $x_now_pixels, $lasty[$idx], $x_now_pixels, $y_now_pixels,
                                      IMG_COLOR_STYLED);
                        } else {
                            ImageLine($this->img, $lastx[$idx], $lasty[$idx], $x_now_pixels, $lasty[$idx],
                                      $this->ndx_data_colors[$idx]);
                            ImageLine($this->img, $x_now_pixels, $lasty[$idx], $x_now_pixels, $y_now_pixels,
                                      $this->ndx_data_colors[$idx]);
                        }
                    }
                    $lastx[$idx] = $x_now_pixels;
                    $lasty[$idx] = $y_now_pixels;
                    $start_lines[$idx] = TRUE;
                }
                // Y data missing... should we leave a blank or not?
                else if ($this->draw_broken_lines) {
                    $start_lines[$idx] = FALSE;
                }
            }
        }   // end while

        ImageSetThickness($this->img, 1);
        return TRUE;
    } // function DrawSquared()


    /*!
     * Data comes in as array("title", x, y, y2, y3, ...)
     */
    protected function DrawBars()
    {
        if ($this->data_type != 'text-data') {
            return $this->PrintError('DrawBars(): Bar plots must be text-data: use function SetDataType("text-data")');
        }

        // This is the X offset from the bar group's label center point to the left side of the first bar
        // in the group. See also CalcBarWidths above.
        $x_first_bar = (($this->records_per_group - 1) * $this->record_bar_width) / 2 - $this->bar_adjust_gap;

        for ($row = 0; $row < $this->num_data_rows; $row++) {
            $record = 1;                                    // Skip record #0 (data label)

            $x_now_pixels = $this->xtr(0.5 + $row);         // Place text-data at X = 0.5, 1.5, 2.5, etc...

            if ($this->x_data_label_pos != 'none')          // Draw X Data labels?
                $this->DrawXDataLabel($this->data[$row][0], $x_now_pixels);

            // Lower left X of first bar in the group:
            $x1 = $x_now_pixels - $x_first_bar;

            // Draw the bars in the group:
            for ($idx = 0; $record < $this->num_recs[$row]; $record++, $idx++) {
                if (is_numeric($this->data[$row][$record])) {       // Allow for missing Y data
                    $x2 = $x1 + $this->actual_bar_width;

                    if ($this->data[$row][$record] < $this->x_axis_position) {
                        $y1 = $this->x_axis_y_pixels;
                        $y2 = $this->ytr($this->data[$row][$record]);
                        $upgoing_bar = False;
                    } else {
                        $y1 = $this->ytr($this->data[$row][$record]);
                        $y2 = $this->x_axis_y_pixels;
                        $upgoing_bar = True;
                    }

                    // Draw the bar
                    ImageFilledRectangle($this->img, $x1, $y1, $x2, $y2, $this->ndx_data_colors[$idx]);

                    if ($this->shading) {                           // Draw the shade?
                        ImageFilledPolygon($this->img, array($x1, $y1,
                                                       $x1 + $this->shading, $y1 - $this->shading,
                                                       $x2 + $this->shading, $y1 - $this->shading,
                                                       $x2 + $this->shading, $y2 - $this->shading,
                                                       $x2, $y2,
                                                       $x2, $y1),
                                           6, $this->ndx_data_dark_colors[$idx]);
                    }
                    // Or draw a border?
                    else {
                        ImageRectangle($this->img, $x1, $y1, $x2,$y2, $this->ndx_data_border_colors[$idx]);
                    }

                    // Draw optional data labels above the bars (or below, for negative values).
                    if ( $this->y_data_label_pos == 'plotin') {
                        if ($upgoing_bar) {
                          $v_align = 'bottom';
                          $y_offset = -5 - $this->shading;
                        } else {
                          $v_align = 'top';
                          $y_offset = 2;
                        }
                        $this->DrawDataLabel($this->fonts['y_label'], $this->y_data_label_angle,
                                $row+0.5, $this->data[$row][$record], $this->ndx_title_color,
                                $this->data[$row][$record], 'center', $v_align,
                                ($idx + 0.5) * $this->record_bar_width - $x_first_bar, $y_offset);
                    }

                }
                // Step to next bar in group:
                $x1 += $this->record_bar_width;
            }   // end for
        }   // end for
        return TRUE;
    } //function DrawBars


    /*!
     * Data comes in as array("title", x, y, y2, y3, ...)
     * \note Original stacked bars idea by Laurent Kruk < lolok at users.sourceforge.net >
     */
    protected function DrawStackedBars()
    {
        if ($this->data_type != 'text-data') {
            return $this->PrintError('DrawStackedBars(): Bar plots must be text-data: use SetDataType("text-data")');
        }

        // This is the X offset from the bar's label center point to the left side of the bar.
        $x_first_bar = $this->record_bar_width / 2 - $this->bar_adjust_gap;

        for ($row = 0; $row < $this->num_data_rows; $row++) {
            $record = 1;                                    // Skip record #0 (data label)

            $x_now_pixels = $this->xtr(0.5 + $row);         // Place text-data at X = 0.5, 1.5, 2.5, etc...

            if ($this->x_data_label_pos != 'none')          // Draw X Data labels?
                $this->DrawXDataLabel($this->data[$row][0], $x_now_pixels);

            // Lower left and lower right X of the bars in this group:
            $x1 = $x_now_pixels - $x_first_bar;
            $x2 = $x1 + $this->actual_bar_width;

            // Draw the bars
            $oldv = 0;
            for ($idx = 0; $record < $this->num_recs[$row]; $record++, $idx++) {
                if (is_numeric($this->data[$row][$record])) {       // Allow for missing Y data

                    $y1 = $this->ytr(abs($this->data[$row][$record]) + $oldv);
                    $y2 = $this->ytr($this->x_axis_position + $oldv);
                    $oldv += abs($this->data[$row][$record]);

                    // Draw the bar
                    ImageFilledRectangle($this->img, $x1, $y1, $x2, $y2, $this->ndx_data_colors[$idx]);

                    if ($this->shading) {                           // Draw the shade?
                        ImageFilledPolygon($this->img, array($x1, $y1,
                                                       $x1 + $this->shading, $y1 - $this->shading,
                                                       $x2 + $this->shading, $y1 - $this->shading,
                                                       $x2 + $this->shading, $y2 - $this->shading,
                                                       $x2, $y2,
                                                       $x2, $y1),
                                           6, $this->ndx_data_dark_colors[$idx]);
                    }
                    // Or draw a border?
                    else {
                        ImageRectangle($this->img, $x1, $y1, $x2,$y2, $this->ndx_data_border_colors[$idx]);
                    }
                }
            }   // end for
        }   // end for
        return TRUE;
    } //function DrawStackedBars


    /*!
     *
     */
    function DrawGraph()
    {
        // Test for missing image, missing data, empty data:
        if (! $this->img) {
            return $this->PrintError('DrawGraph(): No image resource allocated');
        }
        if (empty($this->data) || ! is_array($this->data)) {
            return $this->PrintError("DrawGraph(): No data array");
        }
        if ($this->total_records == 0) {
            return $this->PrintError('DrawGraph(): Empty data set');
        }

        // For pie charts: don't draw grid or border or axes, and maximize area usage.
        // These controls can be split up in the future if needed.
        $draw_axes = ($this->plot_type != 'pie');

        // Get maxima and minima for scaling:
        if (!$this->FindDataLimits())
            return FALSE;

        // Set plot area world values (plot_max_x, etc.):
        if (!$this->CalcPlotAreaWorld())
            return FALSE;

        // Calculate X and Y axis positions in World Coordinates:
        $this->CalcAxisPositions();

        // Process label-related parameters:
        $this->CheckLabels();

        // Calculate the plot margins, if needed.
        // For pie charts, set the $maximize argument to maximize space usage.
        $this->CalcMargins(!$draw_axes);

        // Calculate the actual plot area in device coordinates:
        $this->CalcPlotAreaPixels();

        // Calculate the mapping between world and device coordinates:
        $this->CalcTranslation();

        // Pad color and style arrays to fit records per group:
        $this->PadArrays();
        $this->DoCallback('draw_setup');

        $this->DrawBackground();
        $this->DrawImageBorder();
        $this->DoCallback('draw_image_background');

        $this->DrawPlotAreaBackground();
        $this->DoCallback('draw_plotarea_background', $this->plot_area);

        $this->DrawTitle();
        if ($draw_axes) {  // If no axes (pie chart), no axis titles either
            $this->DrawXTitle();
            $this->DrawYTitle();
        }
        $this->DoCallback('draw_titles');

        if ($draw_axes && ! $this->grid_at_foreground) {   // Usually one wants grids to go back, but...
            $this->DrawYAxis();     // Y axis must be drawn before X axis (see DrawYAxis())
            $this->DrawXAxis();
            $this->DoCallback('draw_axes');
        }

        switch ($this->plot_type) {
        case 'thinbarline':
            $this->DrawThinBarLines();
            break;
        case 'area':
            $this->DrawArea();
            break;
        case 'squared':
            $this->DrawSquared();
            break;
        case 'lines':
            if ( $this->data_type == 'data-data-error') {
                $this->DrawLinesError();
            } else {
                $this->DrawLines();
            }
            break;
        case 'linepoints':
            if ( $this->data_type == 'data-data-error') {
                $this->DrawLinesError();
                $this->DrawDotsError();
            } else {
                $this->DrawLines();
                $this->DrawDots();
            }
            break;
        case 'points';
            if ( $this->data_type == 'data-data-error') {
                $this->DrawDotsError();
            } else {
                $this->DrawDots();
            }
            break;
        case 'pie':
            $this->DrawPieChart();
            break;
        case 'stackedbars':
            $this->CalcBarWidths();
            $this->DrawStackedBars();
            break;
        case 'bars':
        default:
            $this->plot_type = 'bars';  // Set it if it wasn't already set. (necessary?)
            $this->CalcBarWidths();
            $this->DrawBars();
            break;
        }   // end switch
        $this->DoCallback('draw_graph', $this->plot_area);

        if ($draw_axes && $this->grid_at_foreground) {   // Usually one wants grids to go back, but...
            $this->DrawYAxis();     // Y axis must be drawn before X axis (see DrawYAxis())
            $this->DrawXAxis();
            $this->DoCallback('draw_axes');
        }

        if ($draw_axes) {
            $this->DrawPlotBorder();
            $this->DoCallback('draw_border');
        }

        if ($this->legend) {
            $this->DrawLegend();
            $this->DoCallback('draw_legend');
        }
        $this->DoCallback('draw_all', $this->plot_area);

        if ($this->print_image && !$this->PrintImage())
            return FALSE;

        return TRUE;
    } //function DrawGraph()

/////////////////////////////////////////////
//////////////////         DEPRECATED METHODS
/////////////////////////////////////////////

    /*!
     * Deprecated, use SetYTickPos()
     */
    function SetDrawVertTicks($which_dvt)
    {
        if ($which_dvt != 1)
            $this->SetYTickPos('none');
        return TRUE;
    }

    /*!
     * Deprecated, use SetXTickPos()
     */
    function SetDrawHorizTicks($which_dht)
    {
        if ($which_dht != 1)
           $this->SetXTickPos('none');
        return TRUE;
    }

    /*!
     * \deprecated Use SetNumXTicks()
     */
    function SetNumHorizTicks($n)
    {
        return $this->SetNumXTicks($n);
    }

    /*!
     * \deprecated Use SetNumYTicks()
     */
    function SetNumVertTicks($n)
    {
        return $this->SetNumYTicks($n);
    }

    /*!
     * \deprecated Use SetXTickIncrement()
     */
    function SetHorizTickIncrement($inc)
    {
        return $this->SetXTickIncrement($inc);
    }


    /*!
     * \deprecated Use SetYTickIncrement()
     */
    function SetVertTickIncrement($inc)
    {
        return $this->SetYTickIncrement($inc);
    }

    /*!
     * \deprecated Use SetYTickPos()
     */
    function SetVertTickPosition($which_tp)
    {
        return $this->SetYTickPos($which_tp);
    }

    /*!
     * \deprecated Use SetXTickPos()
     */
    function SetHorizTickPosition($which_tp)
    {
        return $this->SetXTickPos($which_tp);
    }

    /*!
     * \deprecated Use SetFont()
     */
    function SetTitleFontSize($which_size)
    {
        return $this->SetFont('title', $which_size);
    }

    /*!
     * \deprecated Use SetFont()
     */
    function SetAxisFontSize($which_size)
    {
        $this->SetFont('x_label', $which_size);
        $this->SetFont('y_label', $which_size);
    }

    /*!
     * \deprecated Use SetFont()
     */
    function SetSmallFontSize($which_size)
    {
        return $this->SetFont('generic', $which_size);
    }

    /*!
     * \deprecated Use SetFont()
     */
    function SetXLabelFontSize($which_size)
    {
        return $this->SetFont('x_title', $which_size);
    }

    /*!
     * \deprecated Use SetFont()
     */
    function SetYLabelFontSize($which_size)
    {
        return $this->SetFont('y_title', $which_size);
    }

    /*!
     * \deprecated Use SetXTitle()
     */
    function SetXLabel($which_xlab)
    {
        return $this->SetXTitle($which_xlab);
    }

    /*!
     * \deprecated Use SetYTitle()
     */
    function SetYLabel($which_ylab)
    {
        return $this->SetYTitle($which_ylab);
    }

    /*!
     * \deprecated Use SetXTickLength() and SetYTickLength() instead.
     */
    function SetTickLength($which_tl)
    {
        $this->SetXTickLength($which_tl);
        $this->SetYTickLength($which_tl);
        return TRUE;
    }

    /*!
     * \deprecated  Use SetYLabelType()
     */
    function SetYGridLabelType($which_yglt)
    {
        return $this->SetYLabelType($which_yglt);
    }

    /*!
     * \deprecated  Use SetXLabelType()
     */
    function SetXGridLabelType($which_xglt)
    {
        return $this->SetXLabelType($which_xglt);
    }
    /*!
     * \deprecated Use SetYTickLabelPos()
     */
    function SetYGridLabelPos($which_yglp)
    {
        return $this->SetYTickLabelPos($which_yglp);
    }
    /*!
     * \deprecated Use SetXTickLabelPos()
     */
    function SetXGridLabelPos($which_xglp)
    {
        return $this->SetXTickLabelPos($which_xglp);
    }


    /*!
     * \deprecated Use SetXtitle()
     */
    function SetXTitlePos($xpos)
    {
        $this->x_title_pos = $xpos;
        return TRUE;
    }

    /*!
     * \deprecated Use SetYTitle()
     */
    function SetYTitlePos($xpos)
    {
        $this->y_title_pos = $xpos;
        return TRUE;
    }

    /*!
     * Draw Labels (not grid labels) on X Axis, following data points. Default position is
     * down of plot. Care must be taken not to draw these and x_tick_labels as they'd probably overlap.
     *
     * \deprecated Use SetXDataLabelPos()
     */
    function SetDrawXDataLabels($which_dxdl)
    {
        if ($which_dxdl == '1' )
            $this->SetXDataLabelPos('plotdown');
        else
            $this->SetXDataLabelPos('none');
    }

    /*!
     * \deprecated
     */
    function SetNewPlotAreaPixels($x1, $y1, $x2, $y2)
    {
        //Like in GD 0, 0 is upper left set via pixel Coordinates
        $this->plot_area = array($x1, $y1, $x2, $y2);
        $this->plot_area_width = $this->plot_area[2] - $this->plot_area[0];
        $this->plot_area_height = $this->plot_area[3] - $this->plot_area[1];
        $this->y_top_margin = $this->plot_area[1];

        if (isset($this->plot_max_x))
            $this->CalcTranslation();

        return TRUE;
    }

    /*!
     * \deprecated Use _SetRGBColor()
     */
    function SetColor($which_color)
    {
        $this->SetRGBColor($which_color);
        return TRUE;
    }

    /*
     * \deprecated Use SetLineWidths().
     */
    function SetLineWidth($which_lw)
    {

        $this->SetLineWidths($which_lw);

        if (!$this->error_bar_line_width) {
            $this->SetErrorBarLineWidth($which_lw);
        }
        return TRUE;
    }

    /*
     * \deprecated Use SetPointShapes().
     */
    function SetPointShape($which_pt)
    {
        $this->SetPointShapes($which_pt);
        return TRUE;
    }

    /*
     * \deprecated Use SetPointSizes().
     */
    function SetPointSize($which_ps)
    {
        $this->SetPointSizes($which_ps);
        return TRUE;
    }
}  // class PHPlot
