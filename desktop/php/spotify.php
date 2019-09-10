<?php

include_file('core', 'authentification', 'php');


if (!isConnect('admin')) {
  throw new Exception('{{401 - Refused access}}');
}

$plugin = plugin::byId('spotify');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());

$code = $_GET['code'];

if( $code != '' ) {
  
	log::add('spotify', 'debug', '### CODE '.$_GET['code'].' ###');   

?>
  
  	<script language='javascript' type='text/javascript'>
    	console.log( '<?php echo $code ?>' );
  		window.opener.spotify_callback( '<?php echo $code ?>' );
    </script>

<?php
      
	die();
  
}

?>
  
<!-- ================================== -->
<!-- Container global (Ligne bootstrap) -->
<!-- ================================== -->
  
<div class="row row-overflow">

  	<!-- =================================== -->
	<!-- Container bootstrap du menu latéral -->
  	<!-- =================================== -->
  
	<div class="col-lg-2 col-md-3 col-sm-4">
  
  		<!-- ========================= -->
		<!-- Container du menu latéral -->
  		<!-- ========================= -->
  
		<div class="bs-sidebar">
  
  			<!-- ============ -->
  			<!-- Menu latéral -->
  			<!-- ============ -->
  
			<ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
  
  				<!-- ============== -->
  				<!-- Bouton d ajout -->
  				<!-- ============== -->
  
          		<a class="btn btn-default eqLogicAction" data-action="add" style="margin-bottom: 5px;width: 100%">
            		<i class="fa fa-plus-circle"></i> {{Ajouter un object}}
          		</a>
                
                <!-- ================= -->
          		<!-- Filtre des objets -->
                <!-- ================= -->
                  
          		<li class="filter" style="margin-bottom: 5px; width: 100%"><input class="filter form-control input-sm" placeholder="{{Rechercher}}"/></li>
          
				<!-- ================ -->
                <!-- Liste des objets -->
                <!-- ================ -->

                <?php foreach ($eqLogics as $eqLogic) : ?>
                	<li class="cursor li_eqLogic" data-eqLogic_id="<?php echo $eqLogic->getId(); ?>">
                		<a><?php echo $eqLogic->getHumanName(true); ?></a>
                    </li>
				<?php endforeach; ?>

         	</ul>
      
  		</div>
  
	</div>

 	<!-- ============================================ -->
	<!-- Container des listes de commandes / éléments -->
  	<!-- ============================================ -->
  
	<div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay">

  		<!-- ===================== -->
        <!—- Container d’une liste -->
  		<!-- ===================== -->
  
      	<legend><i class="fa fa-cog"></i> {{Gestion}}</legend>
  
        <div class="eqLogicThumbnailContainer" style="position: relative; height: 180px">

			<!-- ========================= -->
  			<!-- Bouton d ajout d un objet -->
            <!-- ========================= -->
  
            <div class="cursor eqLogicAction" data-action="add" style="text-align: center; background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px; position: absolute; left: 0px; top: 0px;">
          		<i class="fa fa-plus-circle" style="font-size : 6em;color:#94ca02;"></i>
      			<br>
          		<span style="font-size : 1.1em;position:relative; top : 23px; word-break: break-all; white-space: pre-wrap; word-wrap: break-word;color:#94ca02">{{Ajouter}}</span>
        	</div>
        
  			<!-- ================================= -->
  			<!-- Bouton d accès à la configuration -->
            <!-- ================================= -->
  
  			<div class="cursor eqLogicAction" data-action="gotoPluginConf" style="text-align: center; background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px; ; position: absolute; left: 0px; top: 0px;">
				<i class="fa fa-wrench" style="font-size : 6em;color:#767676;"></i>
				<br>
      			<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">{{Configuration}}</span>
        	</div>

  		</div>
                  
        <!-- ===================== -->
        <!—- Container d’une liste -->
  		<!-- ===================== -->
  
      	<legend><i class="fa fa-cog"></i> {{Mes objets}}</legend>
  
        <div class="eqLogicThumbnailContainer" style="position: relative; height: 180px">

            <!-- ====================== -->
        	<!-- Boucle sur les objects -->
            <!-- ====================== -->
			
            <?php foreach ($eqLogics as $eqLogic) : ?>
            
            	<div class="eqLogicDisplayCard cursor" data-eqLogic_id="<?php echo $eqLogic->getId(); ?>" style="text-align: center; background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;position: absolute; left: 0px; top: 0px;">
              		<i class="fa fa-spotify" style="font-size : 6em;color:#767676;"></i>
              		<br>
              		<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><?php echo $eqLogic->getHumanName(true, true); ?></span>
            	</div>
              
          	<?php endforeach; ?>

  		</div>
  
   	</div>
   
  	<!-- ================================ -->
  	<!-- Container du panneau de contrôle -->
   	<!-- ================================ -->
  	
  	<div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
      
        <!-- ================== -->
    	<!-- Bouton sauvegarder -->
        <!-- ================== -->
      
        <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
      	
        <!-- ================ -->
        <!-- Bouton Supprimer -->
        <!-- ================ -->
              
      	<a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>

        <!-- ================= -->
		<!-- Liste des onglets -->
        <!-- ================= -->
        
		<ul class="nav nav-tabs" role="tablist">
        
        	<!-- Bouton de retour -->
        
            <li role="presentation">
         		<a class="eqLogicAction cursor" aria-controls="home" role="tab" data-action="returnToThumbnailDisplay">
              		<i class="fa fa-arrow-circle-left"></i>
              	</a>
        	</li>
              
        	<!-- Onglet "Equipement" -->
        
            <li role="presentation" class="active">
              	<a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab">
            		<i class="fa fa-tachometer"></i> {{Equipement}}
				</a>
            </li>
        
            <!-- Onglet "Commandes" -->
                  
        	<li role="presentation">
                <a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab">
                  	<i class="fa fa-list-alt"></i> {{Commandes}}
				</a>
          	</li>
                  
      	</ul>
                  
        <!-- ================================ -->
		<!-- Container du contenu des onglets -->
        <!-- ================================ -->

		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
                  
        	<!-- =================== -->
  			<!-- == Error Message == -->
  			<!-- =================== -->
  
  			<div style="display: none;" id="md_spotify_alert"></div>
                  
            <!-- ================================== -->
            <!-- Panneau de modification de l objet -->
            <!-- ================================== -->
        
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">

                <!-- ============================= -->                
                <!-- Bouton d'ajout d'une commande -->
				<!-- ============================= -->                
                  
				<a class="btn btn-info btn-sm cmdAction pull-right" id="tokenize" style="margin-top:5px;"> <i class="fa fa-check-circle"></i> {{Tokenize}}</a>
          		
                <br><br>
                  
                <!-- ================ -->
          		<!-- Ligne de contenu -->
                <!-- ================ -->
          
				<div class="row">
            
                	<!-- =================== -->
                  	<!-- Division en colonne -->
            		<!-- =================== -->
                  
                  	<div class="col-sm-7">
              
                  		<!-- =================== -->
                  		<!-- Début du formulaire -->
                  		<!-- =================== -->
              
                  		<form class="form-horizontal">
                
                  		<!-- ============== -->
                  		<!-- Bloc de champs -->
                  		<!-- ============== -->
                
                  		<fieldset id="spotify_detail">
                  
                  			<div class="form-group">
                    			<label class="col-sm-6 control-label">{{Nom équipement}}</label>
                    			<div class="col-sm-6">
			                    	<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none"/> 
                      				<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
                    			</div>
                  			</div>
                            
                  			<div class="form-group">
                    			<label class="col-sm-6 control-label">{{Etat}}</label>
                    			<div class="col-sm-6">
                      				<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                      				<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                    			</div>
                  			</div>
                  
                  			<div class="form-group">
								<label class="col-sm-6 control-label" >{{Objet parent}}</label>
								<div class="col-sm-6">
									<select class="form-control eqLogicAttr" data-l1key="object_id">
										<option value="">{{Aucun}}</option>
										<?php 
                  
                  							foreach (object::all() as $object) {
												echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
											}
										?>
									</select>
								</div>
							</div>
                                          
                            <div class="form-group">
                    			<label class="col-sm-6 control-label">{{Code}}</label>
                    			<div class="col-sm-6">
			                    	<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="code"/>
                    			</div>
                  			</div>
                                          
                            <div class="form-group">
                    			<label class="col-sm-6 control-label">{{Callback url}}</label>
                    			<div class="col-sm-6">
			                    	<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="callback"/>
                    			</div>
                  			</div>
                                         
                            <div class="form-group">
                    			<label class="col-sm-6 control-label">{{Access token}}</label>
                    			<div class="col-sm-6">
			                    	<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="access"/>
                    			</div>
                  			</div>
                                          
                            <div class="form-group">
                    			<label class="col-sm-6 control-label">{{Refresh token}}</label>
                    			<div class="col-sm-6">
			                    	<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="refresh"/>
                    			</div>
                  			</div>
                            
                  		</fieldset>
                  
              			</form>
                  
					</div>
            
				</div>
                  
			</div>
		
			<div role="tabpanel" class="tab-pane" id="commandtab">
          
                <!-- ============================= -->                
                <!-- Bouton d'ajout d'une commande -->
				<!-- ============================= -->                
                  
				<a class="btn btn-success btn-sm cmdAction pull-right" data-action="add" style="margin-top:5px;"> <i class="fa fa-plus-circle"></i> {{Commandes}}</a>
          		
                <br><br>
                  
          		<!-- ===================== -->
                <!-- Tableau des commandes -->
                <!-- ===================== -->
                  
          		<table id="table_cmd" class="table table-bordered table-condensed">
            		<thead>
                		<tr>
                  			<th>{{Id}}</th>
                  			<th>{{Name}}</th>
                  			<th>{{Type}}</th>
                  			<th>{{Historique}}</th>
                  			<th>{{Actions}}</th>
                		</tr>
                	</thead>
                	<tbody>
                	</tbody>
              	</table>

			</div>
    
		</div>
                  
	</div>
                  
</div>
                  
<?php
                  
include_file('desktop', 'spotify', 'js', 'spotify');                                     
include_file('core', 'plugin.template', 'js');

?>
