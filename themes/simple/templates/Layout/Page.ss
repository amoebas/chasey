<div class="content-container typography">	
	<article>
		<% if IsTagged %>
		<h2> You're tagged!</h2>
		<% end_if %>
		<div class="content">$Content</div>
		
		<div class="friends">
			<% control Players %>
				<div>
					<% if Tagged %><span class="tagged"><% end_if %>
					$Name has been tagged $TagCount times 
					<% if canBeTagged %><a href="player/tag/$ID">Tag this person</a><% end_if %>
					<% if Tagged %> and is currently tagged!!</span><% end_if %>
					</span> 
				</div>
			<% end_control %>
		</div>
	</article>
		$Form
		$PageComments
</div>
<% include SideBar %>