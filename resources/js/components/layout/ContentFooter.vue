<template>
	<footer class="footer px-4">
		<b-row align-v="center" class="justify-content-lg-between">
			<b-col lg="12" class="d-flex justify-content-between">
				<div class="copyright text-center text-lg-left text-muted">
					(Version {{versionApp}})
				</div>
				<div class="copyright text-center text-lg-right text-muted">
					© {{year}} CEN Lorraine. Tous droits réservés.
				</div>
			</b-col>
<!-- 			<b-col lg="6">
				<b-nav align="center" class="nav-footer justify-content-lg-end">
					<b-nav-item href="https://www.creative-tim.com"  target="_blank">
						Creative Tim
					</b-nav-item>
					<b-nav-item href="https://www.creative-tim.com/presentation" target="_blank">
						About Us
					</b-nav-item>
					<b-nav-item href="http://blog.creative-tim.com" target="_blank">
						Blog
					</b-nav-item>
					<b-nav-item href="https://www.creative-tim.com/license" target="_blank">
						License
					</b-nav-item>
				</b-nav>
			</b-col> -->
		</b-row>
	</footer>
</template>

<script>
export default {
	props:['versionApp'],
	data() {
		return {
			year: new Date().getFullYear()
		};
	}
};
</script>
<style></style>
