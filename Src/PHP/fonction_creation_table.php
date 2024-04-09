<?php

function creationTable($result, $role)
{
    echo "<table style='width: 100%; text-align: center'>";

    // Affiche les en-têtes de colonnes
    echo "<tr>";

    if ($result && mysqli_num_rows($result) > 0) {
        $headerPrinted = false;
        while ($row = mysqli_fetch_assoc($result)) {
            // Affiche les en-têtes de colonnes une seule fois
            if (!$headerPrinted) {
                foreach ($row as $key => $value) {
                    if ($role === 'technicien' && $key === 'Id') {
                        continue; // Ignore la colonne 'Id' si le rôle est technicien
                    }
                    echo "<th>$key</th>";
                }
                if ($role === 'technicien') {
                    echo "<th>Sélectionner</th>"; // Ajoute la colonne de sélection à la fin si le rôle est technicien
                }
                echo "</tr>";
                $headerPrinted = true;
            }

            // Affiche les données de chaque ligne avec une case à cocher
            echo "<tr class='table-row' onclick=\"window.location='page_details_ticket.php?id=" . urlencode($row['Id']) . "'\">";
            foreach ($row as $key => $value) {
                if ($role === 'technicien' && $key === 'Id') {
                    continue; // Ignore la colonne 'Id' si le rôle est technicien
                }
                echo "<td>$value</td>";
            }
            if ($role === 'technicien') {
                echo "<td><input type='checkbox' name='tickets[]' value='{$row['Id']}' class='styled-checkbox'></td>"; // Ajoute la case à cocher à la fin si le rôle est technicien
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        // Affiche un message approprié si aucun ticket n'est trouvé
        echo "<th colspan='6'>";

        if ($role === 'technicien') {
            echo "Aucun ticket pris en charge.";
        }elseif ($role === 'utilisateur') {
            echo "Aucun ticket créé.";
        }elseif ($role === 'admin_web'){
            echo "Aucun ticket.";
        } else {
            echo "Aucun ticket trouvé.";
        }

        echo "</th></tr></table>";
    }
}
