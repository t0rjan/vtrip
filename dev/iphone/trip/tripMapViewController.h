//
//  tripMapViewController.h
//  trip
//
//  Created by 沈 吾苓 on 13-2-25.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <UIKit/UIKit.h>
#import<MapKit/MapKit.h>
@interface tripMapViewController : UIViewController<MKMapViewDelegate>
@property (copy , nonatomic) NSMutableArray *photoList;
@end
