                                    <?php 
                                    $table = '';
                                    $thead = '';
                                    for($i=1;$i<=10;$i++){
                                        $thead .= 
                                        '
                                        <th>Heading '.$i.'</th>
                                        ';
                                    }
                                    $thead = 
                                    '
                                    <tr>'.$thead.'</tr>
                                    ';
                                    
                                    
                                    $tr = '';
                                    for($i=1;$i<=10;$i++){
                                        $td = '';
                                        for($j=1;$j<=10;$j++){
                                            $td .=
                                            '
                                            <td>Row '.$i.' Column '.$j.'</td>
                                            ';
                                        }
                                        $tr .= 
                                        '
                                        <tr>'.$td.'</tr>
                                        ';

                                    }
                                    $table = 
                                    '
                                    <table class="table">
                                        <thead>
                                            '.$thead.'
                                        </thead>
                                        <tbody>
                                            '.$tr.'
                                        </tbody>
                                    </table>
                                    ';
                                    echo $table;
                                    ?>



<?php 
                                    $table = '';
                                    $thead = '<th>Channels</th>';
                                    $channels = array('BBC','CNN','Special News','Guardian','TIMES','FOX News','India TV','ABP News','ZEE News');
                                    $brands = array('Kent','Apple','Maruti','Honda','Dell','Intel','Jio','Airtel','Nestle');
                                    foreach($brands as $brand){
                                        $thead .= 
                                        '
                                        <th> '.$brand.'</th>
                                        ';
                                    }
                                    $thead = 
                                    '
                                    <tr>'.$thead.'</tr>
                                    ';
                                    
                                    
                                    $tr = '';
                                    foreach($channels as $channel){
                                        $td = 
                                        '
                                        <td>'.$channel.'</td>
                                        ';
                                        foreach($brands as $brand){
                                            $td .=
                                            '
                                            <td> '.$channel.'  '.$brand.'
                                                <div class="card">
                                                <div class="row">
                                                <img src="'.get_core_theme_path().'images/logo.png" class="col-md-6">
                                                <img src="'.get_core_theme_path().'images/logo-backend.png" class="col-md-6 ">
                                                </div>
                                                    <a href="#" class="btn btn-outline-danger m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">
                                                        <i class="fa flaticon-exclamation"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">
                                                        <i class="fa flaticon-imac"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-outline-danger m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--outline-2x m-btn--pill m-btn--air">
                                                        <i class="fa flaticon-folder-3"></i>
                                                    </a>
                                                </div>                                            
                                            </td>
                                            ';
                                        }
                                        $tr .= 
                                        '
                                        <tr>'.$td.'</tr>
                                        ';

                                    }
                                    $table = 
                                    '
                                    <table class="table">
                                        <thead>
                                            '.$thead.'
                                        </thead>
                                        <tbody>
                                            '.$tr.'
                                        </tbody>
                                    </table>
                                    ';
                                    echo $table;
                                    ?>                                    