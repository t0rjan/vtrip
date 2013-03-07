//
//  tripMapViewController.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-25.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "tripMapViewController.h"

@interface tripMapViewController ()

@property (assign , nonatomic) MKMapView *map;

@end

@implementation tripMapViewController
@synthesize photoList;
@synthesize map;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    self.map = [[MKMapView alloc] initWithFrame:CGRectMake(0, 0, 320, 150)];
    [self.view addSubview:self.map];
    self.view.backgroundColor = [UIColor whiteColor];

}
- (void)viewDidAppear:(BOOL)animated
{
    if ([photoList count] > 0) {
        
        
        NSMutableArray *aLatitude = [[NSMutableArray alloc] init];
        NSMutableArray *aLongtitude = [[NSMutableArray alloc] init];
        CLLocationCoordinate2D aPinCoords[[photoList count]];
        
        int i=0;
        float laSum = 0;
        float longSum = 0;
        for (NSDictionary *photoInfo in photoList) {
            
            laSum += [[photoInfo objectForKey:@"latitude"] floatValue];
            longSum += [[photoInfo objectForKey:@"longtitude"] floatValue];
            CLLocationCoordinate2D loc;
            loc.latitude = [[photoInfo objectForKey:@"latitude"] floatValue];
            loc.longitude =[[photoInfo objectForKey:@"longtitude"] floatValue];
            aPinCoords[i]=loc;
            
            [aLatitude addObject:[photoInfo objectForKey:@"latitude"]];
            [aLongtitude addObject:[photoInfo objectForKey:@"longtitude"]];
            
            MKPointAnnotation *annotation = [[MKPointAnnotation alloc] init];
            annotation.coordinate = loc;
            annotation.title = @"";
            annotation.subtitle = @"这里有好多好多文字，这里有好多好多文字，这里有好多好多文字，这里有好多好多文字，";
            
            [self.map addAnnotation:annotation];
            i++;
        }
        
        MKPolyline *lineOne = [MKPolyline polylineWithCoordinates:aPinCoords count:[photoList count]];
        lineOne.title = @"red";
        [self.map addOverlays:@[lineOne]];
        
        [aLatitude sortedArrayUsingSelector:@selector(compare:)];
        
        float laMid = laSum/[photoList count];
        float longMid = longSum/[photoList count];
        
        
        float laDistance = [[aLatitude objectAtIndex:[aLatitude count]-1] floatValue] - [[aLatitude objectAtIndex:0] floatValue];
        [aLongtitude sortedArrayUsingSelector:@selector(compare:)];
        
        float longDistance = [[aLongtitude objectAtIndex:[aLongtitude count]-1] floatValue] - [[aLongtitude objectAtIndex:0] floatValue];
        float maxDistance = MAX(laDistance, longDistance);
        
        CLLocationCoordinate2D loc;
        loc.latitude = laMid;
        loc.longitude =longMid;
        MKCoordinateSpan span;
        span.latitudeDelta = maxDistance*2;
        span.longitudeDelta = maxDistance*2;
        MKCoordinateRegion region = {loc,span};
        [self.map setRegion:region];
    }
}
- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


- (MKOverlayView *)mapView:(MKMapView *)mapView viewForOverlay:(id<MKOverlay>)overlay
{
    if ([overlay isKindOfClass:[MKPolyline class]]) {
        
        MKPolylineView *lineview=[[[MKPolylineView alloc] initWithOverlay:overlay] autorelease];
        lineview.strokeColor = [UIColor purpleColor];
        lineview.lineWidth=3.0;
        
        return lineview;
    }
    return nil;
}
- (void)mapView:(MKMapView *)mapView didSelectAnnotationView:(MKAnnotationView *)view
{
    CLLocationCoordinate2D a =[view.annotation coordinate];
}

@end
