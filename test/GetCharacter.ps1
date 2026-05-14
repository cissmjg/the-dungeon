 param(
    [Parameter(Mandatory)]
    $PlayerName,
    [Parameter(Mandatory)]
    $CharacterName
 )

 $UriBase = "https://www.themikegsite.com/dnd/dbio/"
 $NormalizedCharacterName = $CharacterName.Replace(' ', '_')
 
 ### Character Details ###
 $UriCharacterDetails = "getPlayerCharacterDetails.php?playerName={0}&characterName={1}" -f $PlayerName, $CharacterName
 $FullUri = $UriBase + $UriCharacterDetails

 $OutputFileName = "C:\\Projects\\the-dungeon\\test\\data\\{0}_character_details.json" -f $NormalizedCharacterName

 Write-Output $FullUri
 Write-Output $OutputFileName

 Invoke-WebRequest -Uri $FullUri -OutFile $OutputFileName

 ### Character Skills ###
 $UriCharacterSkills = "getPlayerCharacterSkillSet.php?playerName={0}&characterName={1}" -f $PlayerName, $CharacterName
 $FullUri = $UriBase + $UriCharacterSkills

 $OutputFileName = "C:\\Projects\\the-dungeon\\test\\data\\{0}_skill_set.json" -f $NormalizedCharacterName

 Write-Output $FullUri
 Write-Output $OutputFileName

 Invoke-WebRequest -Uri $FullUri -OutFile $OutputFileName

 ### Character Weapons ###
 $UriCharacterWeapons = "getPlayerCharacterWeapons.php?playerName={0}&characterName={1}" -f $PlayerName, $CharacterName
 $FullUri = $UriBase + $UriCharacterWeapons

 $OutputFileName = "C:\\Projects\\the-dungeon\\test\\data\\{0}_weapon_set.json" -f $NormalizedCharacterName

 Write-Output $FullUri
 Write-Output $OutputFileName

 Invoke-WebRequest -Uri $FullUri -OutFile $OutputFileName
